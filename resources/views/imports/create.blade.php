@extends('layouts.app')

@section('title', __('Data Import'))

@section('content_header')
    <h1>{{ __('Data Import') }}</h1>
@stop

@section('page-content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('imports.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            @csrf

            <div class="card-body">
                <div class="form-group row">
                    <label for="import-type" class="col-sm-2 col-form-label">{{ __('Import Type') }}</label>

                    <div class="col-sm-10 p-0">
                        <select name="import_type" class="custom-select rounded-1" id="import-type" required>
                            @foreach($importTypes as $key => $importType)
                                <option value="{{ $key }}" @selected($key === old('import_type'))>
                                    {{ $importType['label'] }}
                                </option>
                            @endforeach
                        </select>

                        @error('import_type')
                            <div class="mt-1 mb-2 text-red">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Dynamic File Inputs -->
                <div id="file-inputs-container"></div>

                @error('files*')
                    <div class="mt-1 mb-2 text-red">{{ $message }}</div>
                @enderror
            </div>

            <div class="card-footer bg-transparent mt-4 p-0">
                <div class="col-sm-10 float-right">
                    <button type="submit" class="btn btn-info">{{ __('Import') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const importTypes = @json($importTypes);
            const importSelect = document.getElementById('import-type');
            const fileInputsContainer = document.getElementById('file-inputs-container');

            function renderFileInputs(selectedType) {
                fileInputsContainer.innerHTML = ''; // Clear previous inputs

                const selectedImport = importTypes[selectedType];
                if (selectedImport && selectedImport.files) {
                    Object.keys(selectedImport.files).forEach(fileKey => {
                        const fileConfig = selectedImport.files[fileKey];

                        const fileInputGroup = document.createElement('div');
                        fileInputGroup.className = 'form-group row mt-5';

                        const requiredHeaders = Object.values(fileConfig.headers_to_db)
                            .filter(header => {
                                return header.validation && Array.isArray(header.validation) && header.validation.includes('required');
                            })
                            .map(header => header.label);

                        fileInputGroup.innerHTML = `
                            <label class="col-sm-2 col-form-label">${fileConfig.label}</label>
                            <div class="custom-file col-sm-10">
                                <input type="file" name="files[${fileKey}]" class="custom-file-input" accept=".csv,.xlsx" id="file-${fileKey}">
                                <label class="custom-file-label" for="file-${fileKey}" id="label-${fileKey}">Choose file</label>
                                <p class="form-control-plaintext text-gray text-sm" id="headers-${fileKey}">
                                    Required Headers: ${requiredHeaders.length > 0
                                        ? requiredHeaders.join(', ')
                                        : 'No Required Headers'}
                                </p>
                            </div>
                        `;

                        fileInputsContainer.appendChild(fileInputGroup);

                        // Add event listener to display the uploaded file name
                        const fileInput = document.getElementById(`file-${fileKey}`);
                        const fileLabel = document.getElementById(`label-${fileKey}`);
                        fileInput.addEventListener('change', function () {
                            fileLabel.textContent = this.files[0]?.name || 'Choose file';
                        });
                    });
                }
            }

            // Trigger on change
            importSelect.addEventListener('change', function () {
                renderFileInputs(this.value);
            });

            // Initialize on page load
            if (importSelect.value) {
                renderFileInputs(importSelect.value);
            }
        });
    </script>
@endpush
