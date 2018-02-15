<?php

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UuidAggregateRootIdTest extends TestCase
{
    /**
     * @test
     */
    public function aggregate_root_id_doesnt_change_uuids()
    {
        $uuid = Uuid::uuid4();
        $aggregateId = new UuidAggregateRootId($uuid->toString());
        $this->assertTrue($aggregateId->toUuid()->equals($uuid));
    }
}