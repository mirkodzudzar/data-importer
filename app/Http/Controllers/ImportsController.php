<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use Illuminate\Http\Request;

class ImportsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $importTypes = config('import-types');

        return view('imports.create', compact('importTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImportRequest $request)
    {
        $validated = $request->validated();

        // TODO
    }
}
