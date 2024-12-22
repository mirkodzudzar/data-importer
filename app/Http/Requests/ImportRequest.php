<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $importTypes = config("import-types");

        return [
            'import_type' => ['required', 'string', Rule::in(array_keys($importTypes))],
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:csv,xlsx',
        ];
    }

    public function messages(): array
    {
        return [
            'import_type.required' => __('The import type is required.'),
            'import_type.in' => __('The selected import type is invalid.'),
            'files.required' => __('At least one file must be uploaded.'),
            'files.array' => __('Files must be provided as an array.'),
            'files.min' => __('At least one file is required for import.'),
            'files.*.required' => __('Each file must be uploaded.'),
            'files.*.file' => __('Each file must be a valid file.'),
            'files.*.mimes' => __('Each file must be a CSV or XLSX file.'),
        ];
    }
}
