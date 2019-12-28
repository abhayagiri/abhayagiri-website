<form method="POST" action="{{ lp(route('books.cart.submit', null, false)) }}">

    @csrf

    <div class="form-row">
        <div class="form-group col-md-6">
            @include('books.cart.field', ['name' => 'first_name'])
        </div>
        <div class="form-group col-md-6">
            @include('books.cart.field', ['name' => 'last_name'])
        </div>
    </div>

    <div class="form-group">
        @include('books.cart.field', ['name' => 'email', 'type' => 'email'])
    </div>

    <div class="form-group">
        @include('books.cart.field', ['name' => 'address', 'placeholder' => __('books.address_placeholder')])
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            @include('books.cart.field', ['name' => 'city'])
        </div>
        <div class="form-group col-md-6">
            @include('books.cart.field', ['name' => 'state'])
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            @include('books.cart.field', ['name' => 'zip'])
        </div>
        <div class="form-group col-md-6">
            @include('books.cart.field', ['name' => 'country'])
        </div>
    </div>

    <div class="form-group">
        <label for="comments">{{ __('books.comments') }}</label>
        <textarea class="form-control" name="comments" rows="6">{{ old('comments') }}</textarea>
    </div>

    <div class="form-group">
        {!! NoCaptcha::display() !!}
        @error('g-recaptcha-response')
            <input class="is-invalid" type="hidden">
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary" type="submit">{{ __('common.submit') }}</button>

</form>

@push('scripts')
    {!! NoCaptcha::renderJs(Lang::locale()) !!}
@endpush
