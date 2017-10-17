<?php

namespace Novius\Menu;

/**
 * This trait provides models with some basic overridable behaviour intended for:
 *
 *  - Storing ids and class names of related linkable items (Pages, forms...).
 *  - Storing urls of linkable items.
 *
 * Trait LinkedItems
 * @package App
 */
trait LinkedItems
{
    public static $delimiter = '|';

    /**
     * Returns an array of linkable items.
     *
     * @param string $prefix Label prefix
     * @return mixed
     */
    public static function linkableItems(string $prefix = '')
    {
        return static::all()->mapWithKeys(function ($item) use ($prefix) {
            return [
                implode(static::$delimiter, [$item->id, get_class($item)]) => static::linkableLabel($item->linkableTitle(), $prefix),
            ];
        })->toArray();
    }

    public static function linkableUrls($url, $translation)
    {
        return [$url => $translation];
    }

    /**
     * Builds an array of linkable items and routes to feed a list on the back office
     *
     * @return array
     */
    public static function links()
    {
        $links = [];
        $linkableObjects = config('laravel-menu.linkableObjects', []);
        foreach ($linkableObjects as $class => $translation) {
            $items = $class::linkableItems(trans($translation));
            $links = array_merge($links, $items);
        }

        $linkableUrls = config('laravel-menu.linkableUrls', []);
        foreach ($linkableUrls as $url => $translation) {
            $items = $class::linkableUrls($url, trans($translation));
            $links = array_merge($links, $items);
        }

        asort($links);

        return $links;
    }

    public function linkableUrl()
    {
        return url($this->slug);
    }

    /**
     * Returns the label for the item. Title by default.
     * Optionally overridable within items having no title (for instance name)
     * @return string
     */
    public function linkableTitle()
    {
        return $this->title;
    }

    /**
     * Returns an array:
     *  key: linkable item url
     *  value: linkable item label
     *
     * @param string $link
     * @return array
     */
    public static function linkedItem(string $link)
    {
        list($id, $class) = explode(self::$delimiter, $link);
        $linkedItem = $class::find($id);
        $label = $linkedItem->linkableTitle();
        $url = $linkedItem->linkableUrl();

        return [$url => $label];
    }

    /**
     * It takes both linkableItems and linkableUrls and returns an array or urls and labels.
     * Builds an array of both linkableItems and linkableUrls to feed a list on the front office
     *
     * @param array $links an array of linkableItems and/or linkableUrls
     * @return array An array of url => label
     */
    public static function linkedItemsOrUrlRoutes ($links = [])
    {
        $linkedItemsOrUrlRoutes = [];
        $linkableUrls = config('laravel-menu.linkableUrls', []);

        foreach ($links as $link) {
            $url = null;
            $label = null;
            $linkParts = explode(LinkedItems::$delimiter, $link, 2);

            if (count($linkParts) === 2) { // ex: 23|App\Models\Form\Form
                list($id, $class) = $linkParts;
                $object = $class::find($id);
                $url = $object->linkableUrl();
                $label = $object->linkableTitle();
            } elseif (count($linkParts) === 1 && $link) { // ex: contact
                $url = route($link);
                $label = isset($linkableUrls[$link]) ? trans($linkableUrls[$link]) : '';
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
    protected static function linkableLabel($name, $prefix)
    {
        $label = $name;

        if ($prefix) {
            $label = implode(' - ', [$prefix, $name]);
        }

        return $label;
    }
}
