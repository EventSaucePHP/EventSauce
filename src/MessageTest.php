<?php

namespace EventSauce\EventSourcing;

use PHPStan\Testing\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function accessors()
    {
        $event = EventStub::create('some value');
        $initialHeaders = ['initial' => 'header value'];
        $message = new Message($event, $initialHeaders);
        $this->assertEquals($event->toPayload(), $message->event()->toPayload());
        $this->assertSame($event, $message->event());
        $this->assertEquals($initialHeaders, $message->headers());
    }

    /**
     * @test
     */
    public function aggregate_root_id_accessor()
    {
        $event = EventStub::create('some value');
        $message = new Message($event);
        $this->assertNull($message->aggregateRootId());
        $message = $message->withHeader(Header::AGGREGATE_ROOT_ID, UuidAggregateRootId::create());
        $this->assertInstanceOf(AggregateRootId::class, $message->aggregateRootId());
    }
}