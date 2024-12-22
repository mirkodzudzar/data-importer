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
}
