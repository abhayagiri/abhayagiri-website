<div id="language-switch">
    {{-- $pageOtherLng is defined in PageComposer --}}
    <a href="{{ lp(request()->path(), $pageOtherLng->lng) }}">
        <span>
            <span class="flag {{ $pageOtherLng->cssFlag }}"></span>
            &nbsp;{{ __('common.' . $pageOtherLng->transKey, [], $pageOtherLng->lng ) }}
        </span>
    </a>
</div>
