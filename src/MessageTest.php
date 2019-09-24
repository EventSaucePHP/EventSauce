<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function accessors(): void
    {
        $event = PayloadStub::create('some value');
        $initialHeaders = ['initial' => 'header value'];
        $message = new Message($event, $initialHeaders);
        $this->assertSame($event, $message->event());
        $this->assertEquals($initialHeaders, $message->headers());
    }

    /**
     * @test
     */
    public function accessing_the_version_when_not_set(): void
    {
        $this->expectException(RuntimeException::class);
        (new Message(PayloadStub::create('v')))->aggregateVersion();
    }

    /**
     * @test
     */
    public function aggregate_root_id_accessor(): void
    {
        $event = PayloadStub::create('some value');
        $message = new Message($event);
        $this->assertNull($message->aggregateRootId());
        $message = $message->withHeader(Header::AGGREGATE_ROOT_ID, UuidAggregateRootId::create());
        $this->assertInstanceOf(AggregateRootId::class, $message->aggregateRootId());
    }
}
