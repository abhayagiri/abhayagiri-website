@if ($items->currentPage() > 1 || (!($top ?? false) && $items->hasPages()))
    <section class="pagination">
        {{ $items->links() }}
    </section>
@endif
