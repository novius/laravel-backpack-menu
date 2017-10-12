<a class="btn btn-default btn-xs" href="{{ route('crud.item.index', ['menu' => $entry->getKey()]) }}">
    <i class="fa fa-pencil"></i>
    {{ trans('laravel-menu::menu.items') }}
</a>