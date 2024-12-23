<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DynamicExport;
use App\Models\Import;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function __invoke(Request $request, $type, $file)
    {
        $importConfig = config("import-types.{$type}");

        if (!$importConfig || !isset($importConfig['files'][$file])) {
            abort(404);
        }

        // Check if the model class exists for the import type
        $modelName = Str::singular(Str::studly($type));
        $modelClass = "App\\Models\\{$modelName}";

        if (!class_exists($modelClass)) {
            abort(404);
        }

        // Fetch the latest import entry for the specific type and file
        $latestImport = Import::where('import_type', $type)
            ->where('file_key', $file)
            ->latest()
            ->first();

        if (!$latestImport) {
            abort(404, __('No data available for export.'));
        }

        // Fetch the filtered data
        $query = $modelClass::where('import_id', $latestImport->id);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($importConfig, $file, $searchTerm) {
                foreach (array_keys($importConfig['files'][$file]['headers_to_db']) as $field) {
                    $q->orWhere($field, 'like', '%' . $searchTerm . '%');
                }
            });
        }

        $data = $query->get();

        // Pass data and headers to the export class
        $headers = array_keys($importConfig['files'][$file]['headers_to_db']);

        return Excel::download(new DynamicExport($data, $headers), "{$type}_{$file}_export.xlsx");
    }
}
