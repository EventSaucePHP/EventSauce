<?php

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\DummyAggregateRootId;
use PHPUnit\Framework\TestCase;

class AggregateRootPartitionedOffsetCursorTest extends TestCase
{
    /** @test */
    public function it_can_construct_a_aggregate_root_partitioned_offset_cursor_from_start()
    {
        $id = DummyAggregateRootId::generate();
        $cursor = AggregateRootPartitionedOffsetCursor::fromStart($id);

        $string = $cursor->toString();
        $this->assertEquals($cursor, AggregateRootPartitionedOffsetCursor::fromString($string));
    }
}
