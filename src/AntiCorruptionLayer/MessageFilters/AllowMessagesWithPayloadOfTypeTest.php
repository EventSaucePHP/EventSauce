<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters;

use EventSauce\EventSourcing\AntiCorruptionLayer\StubExcludedEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPrivateEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPublicEvent;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class AllowMessagesWithPayloadOfTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider dpMessagesWithPayloadOfType
     */
    public function matching_all_filters(object $event, bool $expectedOutcome): void
    {
        $filter = new AllowMessagesWithPayloadOfType(StubPublicEvent::class, StubPrivateEvent::class);

        $result = $filter->allows(new Message($event));

        $this->assertEquals($expectedOutcome, $result);
    }

    public function dpMessagesWithPayloadOfType(): iterable
    {
        yield [new StubPublicEvent('yes'), true];
        yield [new StubPrivateEvent('yes'), true];
        yield [new StubExcludedEvent('yes'), false];
    }
}
