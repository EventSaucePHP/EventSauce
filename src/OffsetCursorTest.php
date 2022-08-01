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

    /** @test */
    public function it_can_parse_from_null()
    {
        $cursor = OffsetCursor::fromString(null);
        $this->assertEquals(0, $cursor->offset());
    }
}
