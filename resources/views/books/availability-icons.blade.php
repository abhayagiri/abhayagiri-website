@if($book->request)
<i class="la la-check-circle"></i>
@else
<i class="la la-times-circle"></i>
<i class="la la-amazon"></i>
@endif

@if (true)
<a href="{{ $book->pdf_url }}" target="blank">
    <i class="la la-file-pdf"></i>
</a>
@endif

@if($book->epub_url)
<a href="{{ $book->epub_url }}" target="_blank">
    <i class="la la-file-alt"></i>
</a>
@endif

@if($book->mobi_url)
<a href="{{ $book->mobi_url }}" target="_blank">
    <i class="la la-amazon"></i>
</a>
@endif
