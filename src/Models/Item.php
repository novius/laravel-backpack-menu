<?php

namespace Novius\Backpack\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Novius\Backpack\Menu\LinkedItems;

/**
 * @property mixed locale
 * @property mixed name
 * @property mixed depth
 */
class Item extends Model
{
    use CrudTrait;

    protected $table = 'novius_menu_items';
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
        return $this->hasMany(self::class, 'parent_id')->orderBy('lft');
    }

    /**
     * Returns a view for one menu item.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function link()
    {
        return view('laravel-backpack-menu::item', [
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
     * @return string|null url
     */
    public function href()
    {
        $linkParts = explode(LinkedItems::$delimiter, $this->links, 2);
        $href = null;

        if (count($linkParts) === 2) { // ex: 23|App\Models\Form\Form
            list($id, $class) = $linkParts;
            $object = $class::find($id);
            if ($object) {
                $href = $object->linkableUrl();
            }
        } elseif (count($linkParts) === 1) { // ex: contact
            if ($this->links) {
                $href = route($this->links);
            }
        }

        return $href;
    }

    /**
     * Used in the list of menu items in backpack.
     * The names are indented according to the depth of the item. Improves readability.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nameLabelAccordingToDepth()
    {
        return view('laravel-backpack-menu::item.label', [
            'depth' => $this->depth ?: 0,
            'name' => $this->name,
        ]);
    }

    /**
     * Feeds the fields internal_link and external_link in back-office.
     * Determines if the value stored in the "links" attribute is external or internal and returns both.
     *
     * @param $itemId
     * @return array
     */
    public static function getLinksValues($itemId)
    {
        $internalLinkValue = null;
        $externalLinkValue = null;
        $item = $itemId ? self::find($itemId) : null;

        if ($item) {
            $links = $item->links;
            $linkIsLabel = empty($links);
            $linkIsExternal = filter_var($links, FILTER_VALIDATE_URL) !== false;
            $linkIsInternal = ! $linkIsExternal && ! $linkIsLabel;
            $internalLinkValue = $itemId && $linkIsInternal ? $links : null;
            $externalLinkValue = $itemId && $linkIsExternal ? $links : null;
        }

        return [$internalLinkValue, $externalLinkValue];
    }
}
