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
                implode(static::$delimiter, [$item->id, get_class($item)]) => static::linkableLabel($item->name, $prefix),
            ];
        })->toArray();
    }

    public static function linkableUrls($url, $translation)
    {
        return [$url => $translation];
    }

    /**
     * Builds an array of linkable items to feed a list on the back office
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

        return $links;
    }

    public function linkableUrl()
    {
        return url($this->slug);
    }

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
