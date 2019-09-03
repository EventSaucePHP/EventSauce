<?php

namespace EventSauce\EventSourcing\Integration\EventHandling;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PayloadStub;
use PHPStan\Testing\TestCase;

class EventHandlingTest extends TestCase
{
    /**
     * @test
     */
    public function handling_events_from_messages()
    {
        $handler = new DummyEventHandler();
        $event = PayloadStub::create('value');
        $message = new Message($event);
        $handler->handle($message);
        self::assertEquals($event, $handler->event);
        self::assertEquals($message, $handler->message);
    }
}
