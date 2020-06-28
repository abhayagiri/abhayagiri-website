@if ($entry->deleted_at)
<a href="javascript:void(0)" onclick="restoreEntry(this)"
   class="btn btn-sm btn-link"
   data-route="{{ url($crud->route.'/'.$entry->getKey()).'/restore' }}"
   data-button-type="restore">
    <i class="fa fa-undo"></i> Restore
</a>
@endif

@push('after_scripts') @if ($crud->request->ajax()) @endpush @endif
<script>
    if (typeof restoreEntry != 'function') {
        $("[data-button-type=restore]").unbind('click');
        function restoreEntry(button) {
            var button = $(button);
            var route = button.attr('data-route');
            var row = $("#crudTable a[data-route='"+route+"']").closest('tr');
            $.ajax({
                url: route,
                type: 'POST',
                success: function(result) {
                    swal({
                      title: "Item restored",
                      text: "The item has been restored successfully",
                      icon: "success",
                      timer: 4000,
                      buttons: false,
                    });
                    row.remove();
                }
            });
        }
    }
</script>
@if (!$crud->request->ajax()) @endpush @endif
