<?php

namespace Novius\Backpack\Menu\Http\Controllers\Admin\Menu;

use Novius\Backpack\CRUD\Http\Controllers\CrudController;
use Illuminate\Support\Facades\App;
use Novius\Backpack\Menu\LinkedItems;
use Novius\Backpack\Menu\Models\Item;
use Novius\Backpack\Menu\Http\Requests\Admin\ItemRequest as StoreRequest;
use Novius\Backpack\Menu\Http\Requests\Admin\ItemRequest as UpdateRequest;
use Novius\Backpack\Menu\Models\Menu;

class ItemController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Item::class);
        $this->crud->setRoute(route('crud.item.index'));
        $this->crud->setEntityNameStrings(trans('laravel-backpack-menu::menu.item'), trans('laravel-backpack-menu::menu.items'));

        $this->crud->addFilter([
            'name' => 'id',
            'type' => 'select2',
            'label' => trans('laravel-backpack-menu::menu.menu'),
        ], function () {
            return Menu::all()->pluck('name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'menu_id', $value);
        });

        $this->crud->addButton('top', 'menus', 'view', 'laravel-backpack-menu::buttons.menus', 'beginning');

        $this->crud->addColumn([
            'name' => 'name',
            'label' => trans('laravel-backpack-menu::menu.edit.name'),
            'type' => 'model_function',
            'function_name' => 'nameLabelAccordingToDepth',
        ]);

        $this->crud->addColumn([
            'label' => trans('laravel-backpack-menu::menu.menu'),
            'type' => 'select',
            'name' => 'menu_id',
            'entity' => 'menu',
            'attribute' => 'name',
            'model' => Menu::class,
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => trans('laravel-backpack-menu::menu.edit.name'),
        ]);

        $this->crud->addField([
            'name' => 'menu_id',
            'label' => trans('laravel-backpack-menu::menu.menu'),
            'type' => 'select2_from_array',
            'options' => Menu::all()->pluck('name', 'id')->toArray(),
            'allows_null' => false,
            'allows_multiple' => false,
        ]);

        $this->crud->addField([
            'name' => 'links',
            'label' => 'Link',
            'type' => 'select2_from_array',
            'options' => LinkedItems::links(),
            'allows_null' => true,
            'allows_multiple' => false,
        ]);

        $this->crud->query->where('locale', App::getLocale());
        $this->crud->orderBy('menu_id');
        $this->crud->orderBy('lft');

        $this->configureReorder();
    }

    public function edit($id)
    {
        $item = Item::find($id);
        $this->crud->setIndexRoute('crud.item.index', ['id' => $item->menu_id]);

        return parent::edit($id);
    }

    public function store(StoreRequest $request)
    {
        $request->request->set('locale', App::getLocale());

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        $request->request->set('locale', App::getLocale());

        return parent::updateCrud($request);
    }

    /**
     * Filters items of a concrete menu before reordering
     *
     * @return \Backpack\CRUD\app\Http\Controllers\CrudFeatures\Response
     */
    public function reorder()
    {
        $this->crud->query = $this->crud->query->where('menu_id', request('id'));

        return parent::reorder();
    }

    protected function configureReorder()
    {
        $this->crud->allowAccess('reorder');
        $this->crud->enableReorder('name', config('backpack.laravel-backpack-menu.max_nesting', 5));
        $this->crud->setReorderRoute('crud.item.index', ['id' => request('id')]);
    }
}
