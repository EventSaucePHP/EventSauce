<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters;

use EventSauce\EventSourcing\AntiCorruptionLayer\StubExcludedEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPrivateEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPublicEvent;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class NeverAllowMessagesTest extends TestCase
{
    /**
     * @test
     * @dataProvider dpMessagesToCheck
     */
    public function it_never_allows(object $payload): void
    {
        $filter = new NeverAllowMessages();

        $result = $filter->allows(new Message($payload));

        $this->assertFalse($result);
    }

    public function dpMessagesToCheck(): iterable
    {
        yield [new StubPublicEvent('yes')];
        yield [new StubPrivateEvent('yes')];
        yield [new StubExcludedEvent('yes')];
    }
}
