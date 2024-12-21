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
                                <option value="{{ $key }}" @selected($key === old('import_type'))>{{ $importType['label'] }}</option>
                            @endforeach
                        </select>

                        @error('import_type')
                            <div class="mt-1 mb-2 text-red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="file" class="col-sm-2 col-form-label">DS Sheet</label>

                    <div class="custom-file col-sm-10">
                        <input type="file" name="file" class="custom-file-input" id="file" required accept=".csv,.xlsx">

                        <label class="custom-file-label" for="file">Choose file</label>

                        <p class="form-control-plaintext text-gray text-sm" id="required-headers">
                            @if (!empty($headers))
                                {{ __('Required Headers: ') }}
                                {{ $headers }}
                            @else
                                {{ __('No Required Headers') }}
                            @endif
                        </p>

                        @error('file')
                            <div class="text-red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const importTypes = @json($importTypes);
            const importSelect = document.getElementById('import-type');
            const headersDisplay = document.getElementById('required-headers');

            importSelect?.addEventListener('change', function() {
                const selectedImport = importTypes[this.value];

                if (selectedImport && selectedImport.files?.file1?.headers_to_db) {
                    const requiredHeaders = Object.values(selectedImport.files.file1.headers_to_db)
                        .filter(header =>
                            header.validation &&
                            Array.isArray(header.validation) &&
                            header.validation.includes('required')
                        )
                        .map(header => header.label);

                    headersDisplay.textContent = requiredHeaders.length > 0
                        ? `Required Headers: ${requiredHeaders.join(', ')}`
                        : 'No Required Headers';
                } else {
                    headersDisplay.textContent = 'No Required Headers';
                }
            });

            // Trigger initial load if needed
            if (importSelect && importSelect.value) {
                importSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush


