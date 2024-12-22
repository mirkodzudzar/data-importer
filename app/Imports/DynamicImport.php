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
    protected $columnMap = [];

    public function __construct(string $importType, array $importConfig)
    {
        $this->importType = $importType;
        $this->importConfig = $importConfig;

        // Dynamically determine model class (e.g., 'orders' -> 'App\Models\Order')
        $modelName = Str::singular(Str::studly($importType));
        $this->modelClass = "App\\Models\\{$modelName}";

        // Pre-build column mapping
        $this->buildColumnMap();
    }

    protected function buildColumnMap()
    {
        $headerMapping = $this->importConfig['files']['file1']['headers_to_db'];

        foreach ($headerMapping as $dbField => $fieldConfig) {
            $excelHeader = $this->normalizeHeader($fieldConfig['label']);

            $this->columnMap[$excelHeader] = [
                'db_field' => $dbField,
                'type' => $fieldConfig['type']
            ];
        }
    }

    /**
     * Normalize Excel headers to match with data array keys
     */
    protected function normalizeHeader($header)
    {
        $normalized = strtolower(str_replace(' ', '_', $header));
        // Remove special characters
        $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);

        return $normalized;
    }

    public function model(array $row)
    {
        $this->rowNumber++;
        $data = [];
        $normalizedRow = [];

        // Convert Excel row headers to normalized format
        foreach ($row as $header => $value) {
            $normalizedHeader = $this->normalizeHeader($header);
            $normalizedRow[$normalizedHeader] = $value;
        }

        foreach ($this->columnMap as $excelHeader => $config) {
            $value = $normalizedRow[$excelHeader] ?? null;

            if ($value !== null) {
                switch ($config['type']) {
                    case 'double':
                        $value = (float) str_replace(['$', ','], '', $value);
                        break;
                    case 'date':
                        try {
                            $value = \Carbon\Carbon::parse($value)->format('Y-m-d');
                        } catch (\Exception $e) {
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

            $data[$config['db_field']] = $value;
        }

        return new $this->modelClass($data);
    }

    public function rules(): array
    {
        $rules = [];
        $headerMapping = $this->importConfig['files']['file1']['headers_to_db'];

        foreach ($headerMapping as $dbField => $fieldConfig) {
            $columnName = $this->normalizeHeader($fieldConfig['label']);
            $validationRules = [];

            if (isset($fieldConfig['validation'])) {
                foreach ($fieldConfig['validation'] as $rule => $params) {
                    if (is_numeric($rule)) {
                        $validationRules[] = $params;
                    } else {
                        if ($rule === 'exists') {
                            $validationRules[] = "exists:{$params['table']},{$params['column']}";
                        } elseif ($rule === 'in') {
                            $validationRules[] = 'in:' . implode(',', $params);
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
                'import_type' => $this->importType,
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
