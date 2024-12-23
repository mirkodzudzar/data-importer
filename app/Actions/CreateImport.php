<?php

namespace App\Actions;

use App\Models\User;
use App\Imports\DynamicImport;
use App\Http\Requests\ImportRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CreateImport
{
    public function handle(ImportRequest $request, Authenticatable|User $user): void
    {
        $validated = $request->validated();
        $importType = $validated['import_type'];
        $importConfig = config("import-types.{$importType}");

        if (!$user->hasPermission($importConfig['permission_required'])) {
            abort(403, 'Permission denied.');
        }

        foreach ($validated['files'] as $fileKey => $file) {
            $fileName = $file->getClientOriginalName();
            Excel::import(
                new DynamicImport($importType, $importConfig, $fileKey, $fileName, Auth::id()),
                $file
            );
        }
    }
}
