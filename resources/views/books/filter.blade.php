<form class="d-flex flex-column flex-md-row mb-5" action="{{ route(\Route::currentRouteName()) }}" method="get">
    <div class="form-group mr-4">
        <label class="d-block font-weight-bold small align-bottom" for="author">{{ __('books.author') }}</label>
        <select data-cy="author-select" class="form-control-sm" name="author" id="author">
            <option {{request('author') === 'all' ? 'selected' : ''}} value="all">{{ __('common.all') }}</option>
            @foreach($authors as $author)
                <option
                    {{request('author') === (string) $author->id ? 'selected' : ''}}  value="{{$author->id}}">{{tp($author, 'title')}}
                    ({{ $author->books_count }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mr-4">
        <label class="d-block font-weight-bold small align-bottom" for="language">{{ __('common.language') }}</label>
        <select data-cy="language-select" class="form-control-sm" name="language" id="language">
            <option {{request('language') === 'all' ? 'selected' : ''}}  value="all">{{ __('common.all') }}</option>
            @foreach($languages as $language)
                <option
                    {{request('language') === (string) $language->id ? 'selected' : ''}} value="{{$language->id}}">{{ tp($language, 'title') }}
                    ({{ $language->books_count }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mr-4">
        <label class="d-block font-weight-bold small align-bottom" for="request">{{ __('common.availability') }}</label>
        <select  data-cy="request-select"class="form-control-sm" name="request" id="request">
            <option {{request('request') === 'all' ? 'selected' : ''}} value="all">{{ __('common.all') }}</option>
            <option
                {{request('request') === '1' ? 'selected' : ''}} value="1">{{ __('common.copies_available') }}</option>
            <option
                {{request('request') === '0' ? 'selected' : ''}} value="0">{{ __('common.copies_not_available') }}</option>
        </select>
    </div>
    <div class="align-items-end d-flex form-group">
        <input class="btn btn-secondary btn-sm" type="submit" name="submit" value="{{ __('common.submit') }}" data-cy="filter-books">
    </div>
</form>
