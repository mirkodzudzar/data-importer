<?php

namespace App\Imports;

use App\Models\ImportLog;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class DynamicImport implements ToModel, WithHeadingRow, WithValidation, WithSkipDuplicates, WithChunkReading, SkipsOnFailure, ShouldQueue
{
    protected $importConfig;
    protected $importType;
    protected $modelClass;
    protected $rowNumber = 0;

    public function __construct(string $importType, array $importConfig)
    {
        $this->importType = $importType;
        $this->importConfig = $importConfig;

        // Dynamically determine model class (e.g., 'orders' -> 'App\Models\Order')
        $modelName = Str::singular(Str::studly($importType));
        $this->modelClass = "App\\Models\\{$modelName}";
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->rowNumber++;

        // Get headers_to_db mapping from config
        $headerMapping = $this->importConfig['files']['file1']['headers_to_db'];

        // Prepare data for model creation
        $data = [];
        foreach ($headerMapping as $dbField => $fieldConfig) {
            $columnName = $fieldConfig['label'];
            $value = $row[$columnName] ?? null;

            // Convert value based on type from config
            if ($value !== null) {
                switch ($fieldConfig['type']) {
                    case 'double':
                        $value = (float) str_replace(['$', ','], '', $value); // Remove currency symbols and commas
                        break;
                    case 'date':
                        try {
                            $value = \Carbon\Carbon::parse($value);
                        } catch (\Exception $e) {
                            // Handle invalid date format
                            $value = null;
                        }
                        break;
                    case 'integer':
                        $value = (int) $value;
                        break;
                    case 'boolean':
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        break;
                    case 'string':
                    default:
                        $value = (string) $value;
                        break;
                }
            }

            $data[$dbField] = $value;
        }

        // Dynamically create model instance
        return new $this->modelClass($data);
    }

    public function rules(): array
    {
        // Build validation rules dynamically from config
        $rules = [];
        $headerMapping = $this->importConfig['files']['file1']['headers_to_db'];

        foreach ($headerMapping as $dbField => $fieldConfig) {
            $columnName = strtolower(str_replace(' ', '_', $fieldConfig['label']));
            $validationRules = [];

            if (isset($fieldConfig['validation'])) {
                foreach ($fieldConfig['validation'] as $rule => $params) {
                    if (is_numeric($rule)) {
                        // Simple rule like 'required'
                        $validationRules[] = $params;
                    } else {
                        // Complex rule like 'exists' or 'in'
                        if ($rule === 'exists') {
                            $validationRules[] = "exists:{$params['table']},{$params['column']}";
                        } elseif ($rule === 'in') {
                            $validationRules[] = 'in:'.implode(',', $params);
                        }
                    }
                }
            }

            if (!empty($validationRules)) {
                $rules[$columnName] = $validationRules;
            }
        }

        return $rules;
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            ImportLog::create([
                'import_type' => 'orders',
                'row_number' => $failure->row(),
                'row_data' => $failure->values(),
                'error_column' => $failure->attribute(),
                'error_message' => implode(', ', $failure->errors()),
                'status' => 'validation_failed'
            ]);
        }
    }

    public function startRow(): int
    {
        return 2; // Skip header row
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
