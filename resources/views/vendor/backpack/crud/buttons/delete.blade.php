@if ($crud->hasAccess('delete'))
    @if (method_exists($entry, 'trashed') && $entry->trashed())
        <a href="{{ url($crud->route.'/'.$entry->getKey()) }}/restore" class="btn btn-xs btn-default">
            <i class="fa fa-life-ring"> Restore</i>
        </a>
    @else
    	<a href="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i> {{ trans('backpack::crud.delete') }}</a>
    @endif
@endif
