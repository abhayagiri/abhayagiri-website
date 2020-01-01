@php
    if ($items->currentPage() > 1) {
        $pageIndex = __('common.page_n', ['page' => $items->currentPage()]);
        $title = $pageIndex;
        $breadcrumbs->addBreadcrumb($pageIndex);
    } else {
        $title = null;
    }
@endphp

@if ($title)
    @section('title',  $title)
@endif
