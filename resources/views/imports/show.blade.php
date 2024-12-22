@extends('adminlte::page')

@section('title', __('Imported Data'))

@section('content_header')
    <h1>{{ __('Imported Data') }} - {{ ucfirst($type) }} ({{ ucfirst($file) }})</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        @foreach ($headers as $header)
                            <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                        @endforeach
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) }}">{{ __('No data available for the selected file.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3">
                    {{ $data->links() }}
                </div>
            @endif
        </div>
    </div>
@stop
