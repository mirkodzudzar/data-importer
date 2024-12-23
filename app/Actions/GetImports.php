<?php

namespace App\Actions;

use App\Models\Import;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetImports
{
    public function handle($type = null, $file = null, Request $request = null): array|LengthAwarePaginator
    {
        if (!$type || !$file) {
            return Import::latest()->paginate(10);
        }

        $importConfig = config("import-types.{$type}");
        $modelClass = "App\\Models\\" . Str::singular(Str::studly($type));
        $latestImport = Import::where('import_type', $type)
            ->where('file_key', $file)
            ->latest()
            ->first();

        if (!$latestImport) {
            return [
                'data' => collect(),
                'headers' => array_keys($importConfig['files'][$file]['headers_to_db']),
                'type' => $type,
                'file' => $file,
            ];
        }

        $query = $modelClass::where('import_id', $latestImport->id);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($importConfig, $file, $searchTerm) {
                foreach (array_keys($importConfig['files'][$file]['headers_to_db']) as $field) {
                    $q->orWhere($field, 'like', "%{$searchTerm}%");
                }
            });
        }

        return [
            'data' => $query->paginate(15),
            'headers' => array_keys($importConfig['files'][$file]['headers_to_db']),
            'type' => $type,
            'file' => $file,
        ];
    }
}
