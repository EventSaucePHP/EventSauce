<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventHandling;

use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Message;
use PHPStan\Testing\TestCase;

class EventHandlingTest extends TestCase
{
    /**
     * @test
     */
    public function handling_events_from_messages(): void
    {
        $handler = new DummyEventHandler();
        $event = EventStub::create('value');
        $message = new Message($event);
        $handler->handle($message);
        self::assertEquals($event, $handler->event);
        self::assertEquals($message, $handler->message);
    }
}
