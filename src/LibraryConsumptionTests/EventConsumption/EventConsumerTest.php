<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption;

use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;
use stdClass;

class EventConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function handling_an_event(): void
    {
        $consumer = new EventConsumerStub();
        $message = new Message(new DummyEventForConsuming('Sup.'));
        $consumer->handle(new Message(new stdClass()));
        $this->assertEquals('', $consumer->message);
        $consumer->handle($message);
        $this->assertEquals('Sup.', $consumer->message);
        $this->assertEquals($message, $consumer->messageObject);
    }
}
