<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Time\TestClock;
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
        $message = $message->withHeader(Header::AGGREGATE_ROOT_ID, DummyAggregateRootId::generate());
        $this->assertInstanceOf(AggregateRootId::class, $message->aggregateRootId());
    }

    /**
     * @test
     */
    public function time_of_recording_accessor(): void
    {
        $event = PayloadStub::create('some value');
        $message = new Message($event);
        $timeOfRecording = (new TestClock())->pointInTime();
        $message = $message->withHeader(Header::TIME_OF_RECORDING, $timeOfRecording->toString());
        $this->assertInstanceOf(PointInTime::class, $message->timeOfRecording());
        $this->assertSame($timeOfRecording->toString(), $message->timeOfRecording()->toString());
    }
}
