@if ($crud->hasAccess('create'))
	<a href="{{ route('crud.item.create', ['menu' => request('menu')]) }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>
@endif