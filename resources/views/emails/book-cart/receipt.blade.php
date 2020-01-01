@if ($cart->isEmpty())
    <h2>{{ __('books.no_books_selected') }}</h2>
@else
    <h2>{{ __('books.selection') }}</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>{{ __('books.title') }}</th>
                <th>{{ __('books.author') }}</th>
                <th>{{ __('books.quantity') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $item)
                <tr>
                    <td>
                        <a href="{{ $item->book->url }}">
                            {{ $item->book->title }}
                        </a>
                    </td>
                    <td>{{ $item->book->author_titles }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<h2>{{ __('books.contact') }}</h2>

<p>
    {{ __('books.name') }}:
    {{ $shipping->first_name }} {{ $shipping->last_name }}<br>
    {{ __('books.email') }}:
    <a href="mailto:{{ $shipping->email }}">{{ $shipping->email }}</a>
</p>

<h2>{{ __('books.address') }}</h2>

<p>
    {{ $shipping->first_name }} {{ $shipping->last_name }}<br>
    {{ $shipping->address }}<br>
    {{ $shipping->city }}, {{ $shipping->state }} {{ $shipping->zip }}<br>
    {{ $shipping->country }}
</p>

<h2>{{ __('books.comments') }}</h2>

<blockquote>{{ $shipping->comments }}</blockquote>
