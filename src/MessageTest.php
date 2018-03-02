<?php

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Time\TestClock;
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
        $this->assertEquals($event->timeOfRecording(), $message->event()->timeOfRecording());
        $this->assertSame($event, $message->event());
        $this->assertEquals($initialHeaders, $message->headers());

    }
}