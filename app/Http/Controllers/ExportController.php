<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DynamicExport;
use App\HandlesDynamicImports;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    use HandlesDynamicImports;

    public function __invoke(Request $request, string $type, string $file)
    {
        $importConfig = $this->getImportConfig($type, $file);
        $modelClass = $this->getModelClass($type);
        $latestImport = $this->getLatestImport($type, $file);

        if (!$latestImport) {
            abort(404, __('No data available for export.'));
        }

        $query = $modelClass::where('import_id', $latestImport->id);

        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($importConfig, $file, $searchTerm) {
                foreach (array_keys($importConfig['files'][$file]['headers_to_db']) as $field) {
                    $q->orWhere($field, 'like', '%' . $searchTerm . '%');
                }
            });
        }

        $data = $query->get();
        $headers = array_keys($importConfig['files'][$file]['headers_to_db']);

        return Excel::download(new DynamicExport($data, $headers), "{$type}_{$file}_export.xlsx");
    }
}
