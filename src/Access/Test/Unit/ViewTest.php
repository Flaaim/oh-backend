<?php

namespace App\Access\Test\Unit;

use App\Access\Entity\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testSuccess(): void
    {
        $view = View::create();
        self::assertEquals(0, $view->getValue());
        self::assertEquals(3, $view->getMaxViews());
    }

    public function testIncrement(): void
    {
        $view = View::create();
        $view->increment();
        $view->increment();
        self::assertEquals(2, $view->getValue());
    }
    public function testIncrementFailed(): void
    {
        $view = View::create();
        $view->increment();
        $view->increment();
        $view->increment();
        $view->increment();

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Превышено максимальное количество просмотров!');
        $view->increment();

    }
    public function testIsAccessible(): void
    {
        $view = View::create();
        $view->increment();

        self::assertTrue($view->isAccessible());

    }


}