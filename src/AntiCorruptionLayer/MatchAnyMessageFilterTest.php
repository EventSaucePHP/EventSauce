<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class MatchAnyMessageFilterTest extends TestCase
{
    /**
     * @test
     * @dataProvider dpFilterCombinations
     */
    public function matching_all_filters(array $internalFilters, bool $expectedOutcome): void
    {
        $filter = new MatchAnyMessageFilter(...$internalFilters);

        $result = $filter->allows(new Message(new StubPublicEvent('yes')));

        $this->assertEquals($expectedOutcome, $result);
    }

    public function dpFilterCombinations(): iterable
    {
        yield [[new AllowAllMessages(), new AllowAllMessages()], true];
        yield [[new NeverAllowMessages(), new AllowAllMessages()], true];
        yield [[new AllowAllMessages(), new NeverAllowMessages()], true];
        yield [[new NeverAllowMessages(), new NeverAllowMessages()], false];
    }
}
