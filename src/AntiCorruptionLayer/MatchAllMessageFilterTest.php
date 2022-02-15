<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class MatchAllMessageFilterTest extends TestCase
{
    /**
     * @test
     * @dataProvider dpFilterCombinations
     */
    public function matching_all_filters(array $internalFilters, bool $expectedOutcome): void
    {
        $filter = new MatchAllMessageFilter(...$internalFilters);

        $result = $filter->allows(new Message(new StubPublicEvent('yes')));

        $this->assertEquals($expectedOutcome, $result);
    }

    public function dpFilterCombinations(): iterable
    {
        yield [[new AlwaysAllowingMessageFilter(), new AlwaysAllowingMessageFilter()], true];
        yield [[new NeverAllowingMessageFilter(), new AlwaysAllowingMessageFilter()], false];
        yield [[new AlwaysAllowingMessageFilter(), new NeverAllowingMessageFilter()], false];
        yield [[new NeverAllowingMessageFilter(), new NeverAllowingMessageFilter()], false];
    }
}
