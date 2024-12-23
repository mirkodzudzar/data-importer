<?php

namespace App;

use App\Models\Import;
use Illuminate\Support\Str;

trait HandlesDynamicImports
{
    protected function getModelClass(string $type)
    {
        $modelName = Str::singular(Str::studly($type));
        $modelClass = "App\\Models\\{$modelName}";

        if (!class_exists($modelClass)) {
            abort(404, "Model for type '{$type}' not found.");
        }

        return $modelClass;
    }

    protected function getImportConfig(string $type, string $file)
    {
        $importConfig = config("import-types.{$type}");

        if (!$importConfig || !isset($importConfig['files'][$file])) {
            abort(404, "Configuration for type '{$type}' and file '{$file}' not found.");
        }

        return $importConfig;
    }

    protected function getLatestImport(string $type, string $file)
    {
        return Import::where('import_type', $type)
            ->where('file_key', $file)
            ->latest()
            ->first();
    }
}
