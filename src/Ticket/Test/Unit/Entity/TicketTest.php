<?php

namespace App\Ticket\Test\Unit\Entity;

use App\Ticket\Entity\Ticket;
use App\Ticket\Test\Builder\TicketProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase
{
    #[DataProvider('dataDetailsProvider')]
    public function testUpdateDetails($values, $expected): void
    {
        $ticket = Ticket::fromArray((new TicketProvider())->withImages());

        $ticket->updateDetails($values['name'], $values['cipher'], $values['updatedAt']);

        self::assertEquals($expected['name'], $ticket->getName());
        self::assertEquals($expected['cipher'], $ticket->getCipher());
        self::assertEquals($expected['updatedAt'], $ticket->getUpdatedAt()->format('d.m.Y'));
    }

    public static function dataDetailsProvider(): array
    {
        return [
            'update only name' => [
                ['name' => 'Проверка знаний рабочего люльки', 'cipher' => null, 'updatedAt' => null],
                ['name' => 'Проверка знаний рабочего люльки', 'cipher' => 'ОТ 123.1', 'updatedAt' => '28.11.2025'],
            ],
            'update only name and cipher' => [
                ['name' => 'Проверка знаний рабочего люльки', 'cipher' => 'ОТ 123.2', 'updatedAt' => null],
                ['name' => 'Проверка знаний рабочего люльки', 'cipher' => 'ОТ 123.2', 'updatedAt' => '28.11.2025']
            ],
            'update only name, cipher, updatedAt' => [
                ['name' => 'Проверка знаний рабочего люльки', 'cipher' => 'ОТ 123.2', 'updatedAt' => '29.11.2025'],
                ['name' => 'Проверка знаний рабочего люльки', 'cipher' => 'ОТ 123.2', 'updatedAt' => '29.11.2025'],
            ]
        ];
    }
}