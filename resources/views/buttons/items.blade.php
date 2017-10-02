<a class="btn btn-default btn-xs" href="{{ route('crud.menu.showDetailsRow', ['id' => $entry->getKey()]) }}">
    <i class="fa fa-pencil"></i>
    {{ trans('laravel-menu::menu.items') }}
</a>