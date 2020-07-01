<aside class="date">
    <div class="posted-at">
        <a href="{{ lp($article->path) }}"><i class="fa fa-link"></i></a>
        {{ __('common.posted') }}: @date($article->posted_at)
    </div>
    @if (isset($showUpdated) && $showUpdated && $article->wasUpdatedAfterPosting())
        <div class="updated-at">
            {{ __('common.last_updated') }}: @date($article->updated_at)
        </div>
    @endif
</aside>
