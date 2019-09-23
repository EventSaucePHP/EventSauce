<?php

namespace EventSauce\EventSourcing\Integration\EventConsumption;

use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;
use stdClass;

class EventConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function handling_an_event()
    {
        $consumer = new EventConsumerStub();
        $message = new Message(new DummyEventForConsuming('Sup.'));
        $consumer->handle(new Message(new stdClass()));
        $this->assertEquals('', $consumer->message);
        $consumer->handle($message);
        $this->assertEquals('Sup.', $consumer->message);
    }
}
