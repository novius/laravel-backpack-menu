<?php

namespace Novius\Backpack\Menu\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Novius\Backpack\Menu\LinkedItems;
use Orchestra\Testbench\TestCase;

class LinkedItemsTraitTest extends TestCase
{

    public function testLinkableItemsShouldReturnACollectionOfWellFormedItems()
    {
        $double = \Mockery::mock(LinkedItems::class);
        $double->allows()->all()->andReturns($this->generateDummies(3));
        $linkableItems = $double::linkableItems();
        $this->assertCount(3, $linkableItems);

        foreach ($linkableItems as $objectId => $title) {
            $this->assertCount(2, explode(LinkedItems::$delimiter, $objectId));
            $this->assertNotNull($title);
        }
    }

    protected function generateDummies($quantity = 1) : Collection
    {
        $dummies = [];

        while ($quantity > 0) {
            $dummyDouble = \Mockery::mock(Dummy::class);
            $dummyDouble->allows()->linkableId()->andReturns(random_int(1,10000));
            $dummyDouble->allows()->linkableTitle()->andReturns(str_random());
            $dummies[] = $dummyDouble;
            $quantity--;
        }

        return collect($dummies);
    }

    protected function setConfig()
    {
        Config::set('backpack.laravel-backpack-menu', [
            'prefix' => 'admin',
            'linkableObjects' => [
                'App\Models\Test\ObjectUnderTest' => 'ObjectUnderTest',
            ],
            'linkableUrls' => [
                'contact' => 'Page contact'
            ],
            'max_nesting' => 2,
        ]);
    }
}
