<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class ImportedDataController extends Controller
{
    public function destroy(string $type, int $id)
    {
        if (!Gate::allows('delete-imported-data', $type)) {
            abort(404);
        }

        // Check if the model class exists for the import type
        $modelName = Str::singular(Str::studly($type));
        $modelClass = "App\\Models\\{$modelName}";

        if (!class_exists($modelClass)) {
            abort(404);
        }

        $modelClass::findOrFail($id)->delete();

        return back()->with('success', __('Imported data deleted!'));
    }
}
