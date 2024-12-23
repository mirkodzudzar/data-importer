<?php

namespace App\Http\Controllers;

use App\Models\Import;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\DynamicImport;
use App\Http\Requests\ImportRequest;
use Maatwebsite\Excel\Facades\Excel;

class ImportsController extends Controller
{
    public function index()
    {
        $imports = Import::latest()->paginate(10);

        return view('imports.index', compact('imports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $importTypes = auth()->user()->getAvailableImportTypes();

        return view('imports.create', compact('importTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImportRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $importType = $validated['import_type'];
        $importConfig = config("import-types.{$importType}");
        $permission = $importConfig['permission_required'];

        if (!$user->hasPermission($permission)) {
            abort(404);
        }

        try {
            foreach ($validated['files'] as $fileKey => $file) {
                if (
                    !isset($importConfig['files'][$fileKey])
                    || !isset($importConfig['files'][$fileKey]['headers_to_db'])
                    || count($importConfig['files'][$fileKey]['headers_to_db']) === 0
                ) {
                    return back()->with('error', __('Import failed, no headers found!'));
                }

                $fileName = $file->getClientOriginalName();

                Excel::import(
                    new DynamicImport($importType, $importConfig, $fileKey, $fileName, $user->id),
                    $file
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', __('Import failed: '.$e->getMessage()));
        }

        return back()->with('success', __('Import started successfully! You will be notified once import is over.'));
    }

    public function show(Request $request, $type, $file)
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
            return view('imports.show', [
                'data' => collect(),
                'headers' => array_keys($importConfig['files'][$file]['headers_to_db']),
                'type' => $type,
                'file' => $file,
            ])->with('error', __('No imports found for this file.'));
        }

        // Fetch the data linked to the latest import
        $query = $modelClass::where('import_id', $latestImport->id);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($importConfig, $file, $searchTerm) {
                foreach (array_keys($importConfig['files'][$file]['headers_to_db']) as $field) {
                    $q->orWhere($field, 'like', '%'.$searchTerm.'%');
                }
            });
        }

        $data = $query->latest()->paginate(15);

        // Get headers for the table dynamically from the configuration
        $headers = array_keys($importConfig['files'][$file]['headers_to_db']);

        return view('imports.show', compact('data', 'headers', 'type', 'file'));
    }
}
