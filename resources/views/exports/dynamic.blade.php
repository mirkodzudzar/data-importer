<table>
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                @foreach ($headers as $header)
                    <td>{{ $row->{$header} ?? '' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
