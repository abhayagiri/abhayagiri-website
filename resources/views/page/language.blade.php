<div id="language-switch">
    {{-- $pages is defined in ViewServiceProvider --}}
    @php $other = $pages->otherLngData(); @endphp
    <a href="{{ lp(request()->path(), $other->lng) }}">
        <span>
            <span class="flag {{ $other->cssFlag }}"></span>
            &nbsp;{{ __('common.' . $other->transKey, [], $other->lng ) }}
        </span>
    </a>
</div>
