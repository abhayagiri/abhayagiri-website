@if ($cart->isEmpty())
    <div class="alert alert-warning" role="alert">
        {{ __('books.no_books_selected') }}
    </div>
@else
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('books.title') }}</th>
                <th scope="col">{{ __('books.weight') }}</th>
                <th scope="col">{{ __('books.quantity') }}</th>
                @if ($editable ?? false)
                    <th scope="col"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $item)
                @include('books.cart.item')
            @endforeach
        </tbody>
    </table>
@endif
