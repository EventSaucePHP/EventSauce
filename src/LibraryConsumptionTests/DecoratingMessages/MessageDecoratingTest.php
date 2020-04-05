<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\DecoratingMessages;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecoratorChain;
use EventSauce\EventSourcing\PayloadStub;
use PHPUnit\Framework\TestCase;

class MessageDecoratingTest extends TestCase
{
    /**
     * @test
     */
    public function decorating_messages(): void
    {
        $decorator = new MessageDecoratorChain(new DummyMessageDecorator());
        $event = new PayloadStub('value');
        $message = new Message($event);
        $decoratedMessage = $decorator->decorate($message);
        $this->assertEquals($event, $decoratedMessage->event());
        $this->assertEquals('value', $decoratedMessage->header('dummy'));
    }
}
