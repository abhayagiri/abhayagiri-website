<aside class="date">
    <a href="{{ lp($article->path) }}">🔗</a>
    {{ __('common.posted') }}: @date($article->posted_at)
    @if (isset($showUpdated) && $showUpdated && $article->wasUpdatedAfterPosting())
        <br>
        {{ __('common.last_updated') }}: @date($article->updated_at)
    @endif
</aside>
