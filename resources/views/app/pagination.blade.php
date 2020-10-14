@if ($items->currentPage() > 1 || (!($top ?? false) && $items->hasPages()))
    <section class="pagination">
        {{ $items->appends(request()->query())->links() }}
    </section>
@endif
