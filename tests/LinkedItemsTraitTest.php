<?php

namespace Novius\Backpack\Menu\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Novius\Backpack\Menu\LinkedItems;
use Orchestra\Testbench\TestCase;

class LinkedItemsTraitTest extends TestCase
{
    protected $linkedItem;

    protected function getEnvironmentSetUp($app)
    {
        $this->setConfig();
        $this->setRoutes();

        $this->linkedItem = \Mockery::mock(LinkedItems::class);
        // Mocks static calls of eloquent methods within the trait: static::all()
        $this->linkedItem->allows()->all()->andReturns(self::generateDummies(3));
    }

    public function testLinkableItemsShouldReturnACollectionOfWellFormedItems()
    {
        $linkableItems = $this->linkedItem::linkableItems();
        $this->assertCount(3, $linkableItems);

        foreach ($linkableItems as $objectId => $title) {
            $this->assertCount(2, explode(LinkedItems::$delimiter, $objectId));
            $this->assertNotNull($title);
        }
    }

    public function testLinkableRoutesReturnsAnArrayOfRouteNameAndTranslation()
    {
        $linkableRoutes = config('backpack.laravel-backpack-menu.linkableRoutes', []);

        foreach ($linkableRoutes as $routeName => $translation) {
            $linkableRoute = $this->linkedItem::linkableRoute($routeName, trans($translation));
            $this->assertEquals('contact', key($linkableRoute));
            $this->assertEquals('Page contact', reset($linkableRoute));
        }
    }

    public function testLinksReturnsAnArrayOfWellFormedLinkedItemKey()
    {
        $links = $this->linkedItem::links();
        $this->assertCount(2, $links);
        $linkedItemKey = explode(LinkedItems::$delimiter, key($links));
        $this->assertCount(2, $linkedItemKey);
        $this->assertInternalType('numeric', $linkedItemKey[0]);
    }

    public function testLinksReturnsAWellFormedLinkedItemValue()
    {
        $links = $this->linkedItem::links();
        $linkableObjectValues = explode('-', reset($links));
        $this->assertCount(2, $linkableObjectValues);
        $this->assertEquals('Dummy object ', $linkableObjectValues[0]);
    }

    public function testLinkedItemReturnsAPairOfUrlAndLabel()
    {
        $link = '1|Novius\Backpack\Menu\Tests\Dummy';
        $linkedItem = $this->linkedItem::linkedItem($link);
        $url = key($linkedItem);
        $label = reset($linkedItem);
        $this->assertTrue(filter_var($url, FILTER_VALIDATE_URL) !== false);
        $this->assertNotNull($label);
    }

    public function testLinkableLabelReturnsALabelPrefixed()
    {
        $labelPrefixed = $this->linkedItem::linkableLabel('name', 'prefix');
        $this->assertEquals('prefix - name', $labelPrefixed);
    }

    public function testLinkableLabelReturnsALabelNonPrefixed()
    {
        $labelPrefixed = $this->linkedItem::linkableLabel('name', '');
        $this->assertEquals('name', $labelPrefixed);
    }

    public function testLinkedItemsOrUrlRoutesAreWellFormed()
    {
        $links = [
            '25|Novius\Backpack\Menu\Tests\Dummy',
            'contact',
        ];

        $linkedItemsOrUrlRoutes = $this->linkedItem::linkedItemsOrUrlRoutes($links);
        foreach ($linkedItemsOrUrlRoutes as $url => $label) {
            $this->assertTrue(filter_var($url, FILTER_VALIDATE_URL) !== false);
            $this->assertNotNull($label);
        }
    }

    public static function generateDummies($quantity = 1) : Collection
    {
        $dummies = [];

        while ($quantity > 0) {
            $dummies[] = self::generateDummy();
            $quantity--;
        }

        return collect($dummies);
    }

    public static function generateDummy()
    {
        $dummyDouble = \Mockery::mock(Dummy::class);
        $dummyDouble->allows()->linkableId()->andReturns(random_int(1, 10000));
        $dummyDouble->allows()->linkableTitle()->andReturns(str_random());
        $dummyDouble->allows()->linkableUrl()->andReturns('http://google.es');

        return $dummyDouble;
    }

    /**
     * Defines configuration for testing purposes
     */
    private function setConfig()
    {
        Config::set('backpack.laravel-backpack-menu', [
            'prefix' => 'admin',
            'linkableObjects' => [
                'Novius\Backpack\Menu\Tests\Dummy' => 'Dummy object',
            ],
            'linkableRoutes' => [
                'contact' => 'Page contact',
            ],
            'max_nesting' => 2,
        ]);
    }

    /**
     * Defines routes for testing purposes
     */
    private function setRoutes()
    {
        Route::get('contact', function () {
        })->name('contact');
    }
}
