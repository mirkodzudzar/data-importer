@extends('adminlte::page')

@section('title', __('Imports'))

@section('content_header')
    <h1>{{ __('Imports') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">{{ __('No imports available.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $imports->links() }}
            </div>
        </div>
    </div>
@stop
