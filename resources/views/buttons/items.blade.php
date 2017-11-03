<a class="btn btn-default btn-xs" href="{{ route('crud.item.index', ['id' => $entry->getKey()]) }}">
    <i class="fa fa-list"></i>
    {{ trans('laravel-backpack-menu::menu.items') }}
</a>