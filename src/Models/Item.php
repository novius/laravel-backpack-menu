<?php

namespace Novius\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Novius\Menu\LinkedItems;

/**
 * @property mixed locale
 * @property mixed name
 */
class Item extends Model
{
    use CrudTrait;

    protected $table = 'novius-menu-items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'menu_id',
        'locale',
        'links',
        'title',
    ];

    public $timestamps = true;

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Returns a view for one menu item.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function link()
    {
        return view('laravel-menu::item', [
            'href' => $this->href(),
            'name' => $this->name,
        ]);
    }

    public function hasChildren()
    {
        return $this->children()->get()->isNotEmpty();
    }

    /**
     * Creates an href for the menu item according to its type.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string url
     */
    public function href()
    {
        $linkParts = explode(LinkedItems::$delimiter, $this->links, 2);

        if (count($linkParts) === 2) { // ex: 23|App\Models\Form\Form
            list($id, $class) = $linkParts;
            $object = $class::find($id);
            $href = $object->linkableUrl();
        } elseif (count($linkParts) === 1) { // ex: contact
            if ($this->links) {
                $href = url($this->links);
            } else {
                $href = null;
            }
        } else {
            $href = '#'; // default case
        }

        return $href;
    }
}
