<?php

namespace Novius\Backpack\Menu;

/**
 * This trait provides models with some basic overridable behaviour intended for:
 *
 *  - Storing ids and class names of related linkable items (Pages, forms...).
 *  - Storing urls of linkable items.
 *
 * This is used for building the menu links in front office.
 *
 * Trait LinkedItems
 *
 * @package App
 */
trait LinkedItems
{
    public static $delimiter = '|';

    /**
     * Returns an array of well-formed linkable items.
     * Check out the config file or the readme file to know more about linkable items.
     *
     * @overridable
     * @param string $prefix Label prefix
     * @return array
     */
    public static function linkableItems(string $prefix = ''): array
    {
        return static::all()->mapWithKeys(function ($item) use ($prefix) {
            $objectId = implode(static::$delimiter, [$item->linkableId(), get_class($item)]);
            $title = static::linkableLabel($item->linkableTitle(), $prefix);

            return [
                $objectId => $title,
            ];
        })->toArray();
    }

    /**
     * Returns an array of well-formed linkable route.
     * Check out the config file or the readme file to know more about linkable routes.
     *
     * @overridable
     * @param $routeName
     * @param $translation
     * @return array
     */
    public static function linkableRoute(string $routeName, string $translation): array
    {
        return [$routeName => $translation];
    }

    /**
     * Returns the url of an linkable item.
     *
     * @overridable
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function linkableUrl(): string
    {
        return isset($this->slug) ? url($this->slug) : '';
    }

    /**
     * Returns the label of the linkable item.
     * Title by default.
     *
     * @overridable
     * @return string
     */
    public function linkableTitle(): string
    {
        return isset($this->title) ? $this->title : '';
    }

    /**
     * Returns the id of the linkable item.
     *
     * @overridable
     * @return string
     */
    public function linkableId(): string
    {
        $primaryKey = $this->getKeyName();

        return $this->{$primaryKey};
    }

    /**
     * Returns a sorted collection of linkable items and routes.
     * This collection is used in the back office (backpack) to feed a select list.
     * This select list is intended for adding new menu items.
     *
     * @return array
     */
    final public static function links(): array
    {
        $links = [];
        $linkableObjects = config('backpack.laravel-backpack-menu.linkableObjects', []);
        foreach ($linkableObjects as $class => $translation) {
            $items = $class::linkableItems(trans($translation));
            $links = array_merge($links, $items);
        }

        $linkableRoutes = config('backpack.laravel-backpack-menu.linkableRoutes', []);
        foreach ($linkableRoutes as $routeName => $translation) {
            $items = static::linkableRoute($routeName, trans($translation));
            $links = array_merge($links, $items);
        }

        asort($links);

        return $links;
    }

    /**
     * Returns an array of an url - label pair:
     *  key: linkable item url
     *  value: linkable item label
     *
     * @param string $link It is the linkable item id stored in the database for a menu item.
     * @return array
     */
    final public static function linkedItem(string $link): array
    {
        list($id, $class) = explode(self::$delimiter, $link);
        $linkedItem = $class::find($id);
        $label = $linkedItem->linkableTitle();
        $url = $linkedItem->linkableUrl();

        return [$url => $label];
    }

    /**
     * It takes both linkableItems and linkableRoutes and returns a sorted array or urls and labels.
     * Builds an array of both linkableItems and linkableRoutes in order to feed a list
     * on the front office when creating the menu items.
     *
     * @param array $links an array of linkableItems and/or linkableRoutes
     * @return array An array of url => label
     */
    final public static function linkedItemsOrUrlRoutes(array $links): array
    {
        if (empty($links)) {
            return [];
        }

        $linkedItemsOrUrlRoutes = [];
        $linkableRoutes = config('backpack.laravel-backpack-menu.linkableRoutes', []);

        foreach ($links as $link) {
            $url = null;
            $label = null;
            $linkParts = explode(self::$delimiter, $link, 2);

            if (count($linkParts) === 2) { // ex: 23|App\Models\Form\Form
                list($id, $class) = $linkParts;
                $object = $class::find($id);
                if ($object) {
                    $url = $object->linkableUrl();
                    $label = $object->linkableTitle();
                }
            } elseif (count($linkParts) === 1 && $link) { // ex: contact
                $url = route($link);
                $label = isset($linkableRoutes[$link]) ? trans($linkableRoutes[$link]) : '';
            }

            if ($url && $label) {
                $linkedItemsOrUrlRoutes[$url] = $label;
            }
        }

        asort($linkedItemsOrUrlRoutes);

        return $linkedItemsOrUrlRoutes;
    }

    /**
     * Returns a label, optionally prefixed.
     *
     * @param $name
     * @param $prefix
     * @return string
     */
    final public static function linkableLabel(string $name, string $prefix = ''): string
    {
        $label = $name;

        if ($prefix) {
            $label = implode(' - ', [$prefix, $name]);
        }

        return $label;
    }
}
