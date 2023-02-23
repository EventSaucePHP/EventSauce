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
        $filter = new MatchAllMessageFilters(...$internalFilters);

        $result = $filter->allows(new Message(new StubPublicEvent('yes')));

        $this->assertEquals($expectedOutcome, $result);
    }

    public static function dpFilterCombinations(): iterable
    {
        yield [[new AllowAllMessages(), new AllowAllMessages()], true];
        yield [[new NeverAllowMessages(), new AllowAllMessages()], false];
        yield [[new AllowAllMessages(), new NeverAllowMessages()], false];
        yield [[new NeverAllowMessages(), new NeverAllowMessages()], false];
    }
}
