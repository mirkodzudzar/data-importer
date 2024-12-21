<?php

namespace App\Http\Controllers;

use App\Imports\OrdersImport;
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

        $firstImport = collect($importTypes)->first();

        $headers = collect($firstImport['files']['file1']['headers_to_db'])
            ->filter(function ($item) {
                return isset($item['validation']) && in_array('required', $item['validation']);
            })
            ->map(function ($item) {
                return $item['label'];
            })
            ->implode(', ');

        return view('imports.create', compact('importTypes', 'headers'));
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
            abort(403);
        }

        try {
            Excel::import(
                new DynamicImport($importType, $importConfig),
                $validated['file']
            );
        } catch (\Exception $e) {
            return back()->with('error', __('Import failed: '.$e->getMessage()));
        }

        return back()->with('success', __('Import started successfully!'));
    }
}
