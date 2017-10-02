<?php

namespace Novius\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Item extends Model
{
    use CrudTrait;

    protected $table = 'novius-menu-items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'menu_id',
    ];

    public $timestamps = true;

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
