<tr>

    <td class="title">
        @include('books.title', ['book' => $item->book, 'link' => true])
    </td>

    <td class="weight">
        {{ $item->book->weight }}
    </td>

    <td class="quantity">
        @if ($editable ?? false)
            <form class="form-inline" method="POST" action="{{ lp(route('books.cart.update', null, false)) }}">
                @csrf
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="id" value="{{ $item->book->id }}">
                <div class="input-group">
                    <input type="number" class="form-control" name="quantity" value="{{ $item->quantity }}" size="3">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-light">
                            {{ __('common.update') }}
                        </button>
                    </div>
                </div>
            </form>
        @else
            {{ $item->quantity }}
        @endif
    </td>

    @if ($editable ?? false)
        <td class="remove">
            <form class="form-inline" method="POST" action="{{ lp(route('books.cart.update', null, false)) }}">
                @csrf
                <input type="hidden" name="_method" value="delete">
                <input type="hidden" name="id" value="{{ $item->book->id }}">
                <button type="submit" class="btn btn-light">
                    {{ __('common.remove') }}
                </button>
            </form>
        </td>
    @endif
</tr>
