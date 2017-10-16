<?php

namespace Novius\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\App;

/**
 * @property mixed name
 */
class Menu extends Model
{
    use CrudTrait;
    use Sluggable;

    protected $table = 'novius_menus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = true;

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_name',
            ],
        ];
    }

    public function getSlugOrNameAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }

        return $this->name;
    }

    /**
     * Returns a view for the menu.
     *
     * @param $slug The slug identifies the menu
     * @param null $locale The locale version of the menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function display($slug, $locale = null)
    {
        $menu = self::where('slug', '=', $slug)->firstOrFail();
        $items = Item::where('menu_id', '=', $menu->id)
                    ->where('locale', '=', $locale ?: App::getLocale())
                    ->orderBy('lft', 'asc')
                    ->get()
                    ->keyBy('id');

        return view('laravel-menu::menu', ['items' => $items]);
    }
}
