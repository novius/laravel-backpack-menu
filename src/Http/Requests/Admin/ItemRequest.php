<?php

namespace Novius\Backpack\Menu\Http\Requests\Admin;

use Backpack\CRUD\app\Http\Requests\CrudRequest;

class ItemRequest extends CrudRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'menu_id' => 'required',
        ];
    }
}
