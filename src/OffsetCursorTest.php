<?php

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

class OffsetCursorTest extends TestCase
{
    /** @test */
    public function it_parses_to_and_from_string()
    {
        $cursor = OffsetCursor::withOffset(5);
        $string = $cursor->toString();
        $this->assertEquals($cursor, OffsetCursor::fromString($string));
    }
}
