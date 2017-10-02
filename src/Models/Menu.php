<?php

namespace Novius\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Menu extends Model
{
    use CrudTrait;

    protected $table = 'novius-menus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
