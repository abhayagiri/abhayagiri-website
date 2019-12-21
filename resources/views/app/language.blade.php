{{-- $otherLngData is defined in ViewServiceProvider --}}
<div id="language-switch">
    <a href="{{ $otherLngData->path }}">
        <span>
            <span class="flag {{ $otherLngData->cssFlag }}"></span>
            &nbsp;{{ __('common.' . $otherLngData->transKey, [], $otherLngData->lng ) }}
        </span>
    </a>
</div>
