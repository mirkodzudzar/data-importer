@extends('adminlte::page')

@section('title', __('Imports'))

@section('content_header')
    <h1>{{ __('Imports') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Import Type') }}</th>
                        <th>{{ __('File Key') }}</th>
                        <th>{{ __('File Name') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Updated At') }}</th>
                        <th style="width: 10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($imports as $index => $import)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $import->user->name ?? 'N/A' }}</td>
                            <td>{{ $import->import_type }}</td>
                            <td>{{ $import->file_key }}</td>
                            <td>{{ $import->file_name }}</td>
                            <td>
                                <span class="badge bg-{{ $import->status->getColor() }}">
                                    {{ $import->status->getLabel() }}
                                </span>
                            </td>
                            <td>{{ $import->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $import->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <button
                                    class="btn btn-link btn-sm view-logs-btn"
                                    data-logs='@json($import->logs)'
                                    data-file-name="{{ $import->file_name }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">{{ __('No imports available.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $imports->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="logsModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logsModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="logsModalBody">{{ __('No logs available.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('logsModal');
            const modalTitle = document.getElementById('logsModalTitle');
            const modalBody = document.getElementById('logsModalBody');

            document.querySelectorAll('.view-logs-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const logs = this.getAttribute('data-logs') ? JSON.parse(this.getAttribute('data-logs')) : [];
                    const fileName = this.getAttribute('data-file-name') || '{{ __('Unknown File') }}';

                    // Update modal title
                    modalTitle.textContent = `Logs for ${fileName}`;

                    // Build logs content
                    if (logs.length > 0) {
                        modalBody.innerHTML = `<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Row Number') }}</th>
                                    <th>{{ __('Error Column') }}</th>
                                    <th>{{ __('Invalid Value') }}</th>
                                    <th>{{ __('Error Message') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${logs.map((log, index) => `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${log.row_number ?? ''}</td>
                                        <td>${log.error_column ?? ''}</td>
                                        <td>${log.invalid_value ?? ''}</td>
                                        <td>${log.error_message ?? ''}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>`;
                    } else {
                        modalBody.innerHTML = '<p>{{ __('No logs available.') }}</p>';
                    }

                    // Show modal
                    modal.style.display = 'block';
                    modal.classList.add('show');
                });
            });

            // Close modal
            document.querySelectorAll('[data-dismiss="modal"]').forEach(button => {
                button.addEventListener('click', () => {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                });
            });
        });
    </script>
@endpush
