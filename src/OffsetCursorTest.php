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
    public function creating_one_from_offset(): void
    {
        $cursor = OffsetCursor::fromOffset(1234);

        self::assertEquals(1234, $cursor->offset());
    }

    /**
     * @test
     */
    public function creating_one_from_offset_with_set_limit(): void
    {
        $cursor = OffsetCursor::fromOffset(1234, limit: 4321);

        self::assertEquals(1234, $cursor->offset());
        self::assertEquals(4321, $cursor->limit());
    }

    /**
     * @test
     */
    public function plussing_an_offset(): void
    {
        $cursor = OffsetCursor::fromStart()
            ->plusOffset(20);

        self::assertEquals(20, $cursor->offset());
        self::assertIsInt($cursor->limit());
    }
}
