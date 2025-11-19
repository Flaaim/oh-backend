<?php

namespace Test\Functional\Telegram\Service;

use Test\Functional\WebTestCase;

class ChannelCheckerRequest extends WebTestCase
{
    public function testTrue(): void
    {
        $result = $this->channelChecker()->checkChannel(1954013093);
        self::assertTrue($result);
    }
    public function testFalse(): void
    {
        $result = $this->channelChecker()->checkChannel(5203091559);
        self::assertFalse($result);
    }
}