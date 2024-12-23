<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Actions\GetImports;
use Illuminate\Http\Request;
use App\Actions\CreateImport;
use App\HandlesDynamicImports;
use App\Http\Requests\ImportRequest;

class ImportsController extends Controller
{
    use HandlesDynamicImports;

    public function index()
    {
        $imports = Import::query()
            ->with('user', 'logs')
            ->latest()
            ->paginate(10);

        return view('imports.index', compact('imports'));
    }

    public function create()
    {
        $importTypes = auth()->user()->getAvailableImportTypes();

        return view('imports.create', compact('importTypes'));
    }

    public function store(ImportRequest $request, CreateImport $creator)
    {
        $creator->handle($request, auth()->user());

        return back()->with('success', __('Import started successfully!'));
    }

    public function show(Request $request, $type, $file, GetImports $getter)
    {
        $response = $getter->handle($type, $file, $request);

        return view('imports.show', $response);
    }
}
