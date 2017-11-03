<?php

namespace Novius\Backpack\Menu\Tests;

use Orchestra\Testbench\TestCase;

class BaseTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
