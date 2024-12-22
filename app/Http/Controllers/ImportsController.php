<?php

namespace App\Http\Controllers;

use App\Imports\DynamicImport;
use App\Http\Requests\ImportRequest;
use Maatwebsite\Excel\Facades\Excel;

class ImportsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $importTypes = config('import-types');

        $importTypes = array_filter($importTypes, function ($importType) {
            return auth()->user()->hasPermission($importType['permission_required']);
        });

        return view('imports.create', compact('importTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImportRequest $request)
    {
        $validated = $request->validated();

        $importType = $validated['import_type'];
        $importConfig = config("import-types.{$importType}");
        $permission = $importConfig['permission_required'];

        if (!auth()->user()->hasPermission($permission)) {
            abort(404);
        }

        try {
            foreach ($validated['files'] as $fileKey => $file) {
                if (!isset($importConfig['files'][$fileKey])) {
                    continue;
                }

                $fileName = $file->getClientOriginalName();

                Excel::import(
                    new DynamicImport($importType, $importConfig, $fileKey, $fileName),
                    $file
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', __('Import failed: '.$e->getMessage()));
        }

        return back()->with('success', __('Import started successfully! Files are being processed in the background.'));
    }
}
