<?php

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AggregateRootIdTest extends TestCase
{
    /**
     * @test
     */
    public function aggregate_root_id_doesnt_change_uuids()
    {
        $uuid = Uuid::uuid4();
        $aggregateId = new AggregateRootId($uuid->toString());
        $this->assertTrue($aggregateId->toUuid()->equals($uuid));
    }
}