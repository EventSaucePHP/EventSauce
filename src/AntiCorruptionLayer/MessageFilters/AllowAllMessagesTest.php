<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters;

use EventSauce\EventSourcing\AntiCorruptionLayer\StubExcludedEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPrivateEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPublicEvent;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class AllowAllMessagesTest extends TestCase
{
    /**
     * @test
     * @dataProvider dpMessagesToCheck
     */
    public function it_always_allows(object $payload): void
    {
        $filter = new AllowAllMessages();

        $result = $filter->allows(new Message($payload));

        $this->assertTrue($result);
    }

    public function dpMessagesToCheck(): iterable
    {
        yield [new StubPublicEvent('yes')];
        yield [new StubPrivateEvent('yes')];
        yield [new StubExcludedEvent('yes')];
    }
}
