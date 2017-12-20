<?php

namespace Novius\Backpack\Menu\Tests;

use Illuminate\Database\Eloquent\Model;
use Novius\Backpack\Menu\LinkedItems;

/**
 * I have made this class because some methods within the trait instantiate objects
 * for a given class and calls eloquent methods. For instance the method linkedItem.
 * I needed a way of dynamically overriding eloquent methods without polluting the trait.
 *
 * Class Dummy
 *
 * @package Novius\Backpack\Menu\Tests
 */
class Dummy extends Model
{
    use LinkedItems;

    /**
     * Overrides eloquent method
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function all($columns = ['*'])
    {
        return LinkedItemsTraitTest::generateDummies();
    }

    /**
     * Overrides eloquent method
     * @param mixed $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model|\Mockery\MockInterface|null|static|static[]
     */
    public static function find($id, $columns = ['*'])
    {
        return LinkedItemsTraitTest::generateDummy();
    }
}
