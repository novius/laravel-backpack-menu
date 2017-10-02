<?php

namespace Novius\Menu\Http\Controllers\Admin\Menu;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Novius\Menu\Models\Menu;
use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;

class MenuController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Menu::class);
        $this->crud->setRoute(config('laravel-menu.prefix', 'admin').'/menu');
        $this->crud->setEntityNameStrings(trans('laravel-menu::menu.menu'), trans('laravel-menu::menu.menus'));

        $this->crud->addColumn([
            'name' => 'name',
            'label' => trans('laravel-menu::menu.edit.name'),
        ]);

        $this->crud->addButton('line', 'items', 'view', 'laravel-menu::buttons.items', 'beginning');

        $this->crud->addField([
            'name' => 'name',
            'label' => trans('laravel-menu::menu.edit.name'),
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }

    public function destroy($id)
    {
        Menu::find($id)->items()->delete();

        return parent::destroy($id);
    }
}
