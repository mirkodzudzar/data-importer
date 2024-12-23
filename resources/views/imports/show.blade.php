@extends('adminlte::page')

@section('title', __('Imported Data'))

@section('content_header')
    <h1>{{ __('Imported Data') }} - {{ ucfirst($type) }} ({{ ucfirst($file) }})</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('imports.show', ['type' => $type, 'file' => $file]) }}" class="d-flex">
                <input
                    type="text"
                    name="search"
                    class="form-control me-2"
                    value="{{ request('search') }}"
                    placeholder="{{ __('Search in all columns...') }}">

                <button type="submit" class="btn btn-primary ml-2">{{ __('Filter') }}</button>

                <a href="{{ route('imports.show', ['type' => $type, 'file' => $file]) }}" class="btn btn-secondary ms-2 ml-2">{{ __('Reset') }}</a>

                <a href="{{ route('export', ['type' => $type, 'file' => $file]) }}?search={{ request('search') }}" class="btn btn-success ms-2 ml-2">
                    {{ __('Export') }}
                </a>
            </form>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        @foreach ($headers as $header)
                            <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                        @endforeach

                        @can('delete-imported-data', $type)
                            <th>{{ __('Actions') }}</th>
                        @endcan
                    </tr>
                </thead>

                <tbody>
                    @forelse ($data as $index => $row)
                        <tr>
                            @foreach ($headers as $header)
                                <td>
                                    @if (isset($row->{$header}) && \Carbon\Carbon::hasFormat($row->{$header}, 'Y-m-d'))
                                        {{ \Carbon\Carbon::parse($row->{$header})->format('n/j/Y') }}
                                    @else
                                        {{ $row->{$header} ?? '' }}
                                    @endif
                                </td>
                            @endforeach

                            @can('delete-imported-data', $type)
                                <td>
                                    <form action="{{ route('imported-data.destroy', [$type, $row->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-danger ml-2" title="{{ __('Delete') }}"
                                            onclick="return confirm('{{ __('Are you sure you want to delete this entry from') }} {{ $type }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) + 1 }}">{{ __('No data available for the selected file.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@stop
