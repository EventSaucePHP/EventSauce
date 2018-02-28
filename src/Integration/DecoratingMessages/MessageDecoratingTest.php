<?php

namespace EventSauce\EventSourcing\Integration\DecoratingMessages;

use EventSauce\EventSourcing\DelegatingMessageDecorator;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\UuidAggregateRootId;
use PHPUnit\Framework\TestCase;

class MessageDecoratingTest extends TestCase
{
    /**
     * @test
     */
    public function decorating_messages()
    {
        $decorator = new DelegatingMessageDecorator(new DummyMessageDecorator());
        $event = new DummyDecoratedEvent();
        $message = new Message($event);
        $decoratedMessage = $decorator->decorate($message);
        $this->assertEquals($event, $decoratedMessage->event());
        $this->assertEquals('value', $decoratedMessage->metadataValue('dummy'));
    }
}