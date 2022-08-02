<?php

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

class OffsetCursorTest extends TestCase
{
    /**
     * @test
     */
    public function from_start_has_offset_zero(): void
    {
        $cursor = OffsetCursor::fromStart();

        self::assertEquals(0, $cursor->offset());
        self::assertIsInt($cursor->limit());
    }

    /**
     * @test
     */
    public function splussing_an_offset(): void
    {
        $cursor = OffsetCursor::fromStart()
            ->plusOffset(20);

        self::assertEquals(20, $cursor->offset());
        self::assertIsInt($cursor->limit());
    }
}