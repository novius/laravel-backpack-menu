<?php

namespace Novius\Backpack\Menu\Http\Controllers\Admin\Menu;

use Illuminate\Support\Facades\Request;
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
        $menuId = Request::input('menu');

        $this->crud->setModel(Item::class);
        $this->crud->setRoute(route('crud.item.index', ['menu' => $menuId]));
        $this->crud->setEntityNameStrings(trans('laravel-backpack-menu::menu.item'), trans('laravel-backpack-menu::menu.items'));
        $this->crud->setEditView('laravel-backpack-menu::edit');

        $this->crud->addFilter([
            'name' => 'menu',
            'type' => 'select2',
            'label' => trans('laravel-backpack-menu::menu.menu'),
            'value' => $menuId,
        ], function () {
            return Menu::all()->pluck('name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'menu_id', $value);
        });

        $this->crud->addButton('top', 'create', 'view', 'laravel-backpack-menu::buttons.create', 'beginning');
        $this->crud->addButton('top', 'menus', 'view', 'laravel-backpack-menu::buttons.menus', 'beginning');
        $this->crud->addButton('top', 'reorder', 'view', 'laravel-backpack-menu::buttons.reorder', 'end');

        $this->crud->addButton('line', 'update', 'view', 'laravel-backpack-menu::buttons.update');
        $this->crud->addButton('line', 'delete', 'view', 'laravel-backpack-menu::buttons.delete');

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
            'box' => trans('laravel-backpack-menu::menu.box.properties'),
        ]);

        $this->crud->addField([
            'name' => 'menu_id',
            'label' => trans('laravel-backpack-menu::menu.menu'),
            'value' => $menuId ?: null,
            'type' => 'select2_from_array',
            'options' => Menu::all()->pluck('name', 'id')->toArray(),
            'allows_null' => false,
            'allows_multiple' => false,
            'box' => trans('laravel-backpack-menu::menu.box.properties'),
        ]);

        list($internalLinkValue, $externalLinkValue) = Item::getLinksValues(Request::route('item'));

        $this->crud->addField([
            'name' => 'internal_link',
            'label' => trans('laravel-backpack-menu::menu.internal_link'),
            'value' => $internalLinkValue,
            'type' => 'select2_from_array',
            'options' => LinkedItems::links(),
            'allows_null' => true,
            'allows_multiple' => false,
            'box' => implode('. ', [trans('laravel-backpack-menu::menu.box.links.label'), trans('laravel-backpack-menu::menu.box.links.description')]),
        ]);

        $this->crud->addField([   // URL
            'name' => 'external_link',
            'label' => trans('laravel-backpack-menu::menu.external_link'),
            'value' => $externalLinkValue,
            'type' => 'url',
            'box' => implode('. ', [trans('laravel-backpack-menu::menu.box.links.label'), trans('laravel-backpack-menu::menu.box.links.description')]),
        ]);

        $this->crud->query->where('locale', App::getLocale());
        $this->crud->query->where('menu_id', $menuId);
        $this->crud->orderBy('menu_id');
        $this->crud->orderBy('lft');

        $this->configureReorder();
    }

    /**
     * Adds the menu id to be injected in the html.
     * Removes options from saveActions menu.
     *
     * @return array
     */
    public function getSaveAction()
    {
        $saveAction = parent::getSaveAction();
        $saveAction['active']['value'] = Request::input('menu');
        unset($saveAction['options']);

        return $saveAction;
    }
    /**
     * Adds the menu id to the save action url
     *
     * @param null $itemId
     * @return mixed
     */
    public function performSaveAction($itemId = null)
    {
        $menu = \Request::input('save_action', config('backpack.crud.default_save_action', 'save_and_back'));

        return \Redirect::to( route('crud.item.index').'?menu='.$menu);
    }

    public function store(StoreRequest $request)
    {
        $this->feedModel($request);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        $this->feedModel($request);

        return parent::updateCrud($request);
    }

    protected function feedModel($request)
    {
        $request->request->set('locale', App::getLocale());
        $internalLink = $request->request->get('internal_link');
        $externalLink = $request->request->get('external_link');
        $request->request->set('links', $internalLink ?: $externalLink);
    }

    /**
     * Filters items of a concrete menu before reordering
     *
     * @return \Backpack\CRUD\app\Http\Controllers\CrudFeatures\Response
     */
    public function reorder()
    {
        $this->crud->query = $this->crud->query->where('menu_id', request('menu'));

        return parent::reorder();
    }

    protected function configureReorder()
    {
        $this->crud->allowAccess('reorder');
        $this->crud->enableReorder('name', config('backpack.laravel-backpack-menu.max_nesting', 5));
        $this->crud->setReorderRoute('crud.item.index', ['menu' => request('menu')]);
    }
}
