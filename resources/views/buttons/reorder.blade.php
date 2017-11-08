@if ($crud->reorder)
    @if ($crud->hasAccess('reorder'))
        <a href="{{ route('crud.item.reorder', ['menu' => request('menu')]) }}" class="btn btn-default ladda-button" data-style="zoom-in">
            <span class="ladda-label">
                <i class="fa fa-arrows"></i>
                {{ trans('backpack::crud.reorder') }} {{ $crud->entity_name_plural }}
            </span>
        </a>
    @endif
@endif