<table class="table table-striped table-bordered">
    <thead></thead>
    <tbody>
        @foreach ($danalist as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td><a href="{{ $item->short_link }}">{{ $item->short_link }}</a></td>
                <td>{{ tp($item, 'summary', $lng) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
