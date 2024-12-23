<?php

namespace App\Http\Controllers;

use App\HandlesDynamicImports;
use Illuminate\Support\Facades\Gate;

class ImportedDataController extends Controller
{
    use HandlesDynamicImports;

    public function destroy(string $type, int $id)
    {
        if (!Gate::allows('delete-imported-data', $type)) {
            abort(403, 'Permission denied.');
        }

        $modelClass = $this->getModelClass($type);
        $modelClass::findOrFail($id)->delete();

        return back()->with('success', __('Imported data deleted!'));
    }
}
