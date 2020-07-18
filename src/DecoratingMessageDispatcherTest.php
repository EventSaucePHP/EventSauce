<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\CollectingMessageDispatcher;
use EventSauce\EventSourcing\DecoratingMessageDispatcher;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class DecoratingMessageDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function dispatching_a_message(): void
    {
        $internalDispatcher = new CollectingMessageDispatcher();
        $dispatcher = new DecoratingMessageDispatcher($internalDispatcher);

        $dispatcher->dispatch(new Message(new EventStub('value')));

        $messages = $internalDispatcher->collectedMessages();
        $this->assertCount(1, $messages);
        $message = $messages[0];
        $headers = $message->headers();
        $this->assertArrayHasKey(Header::EVENT_TYPE, $headers);
    }
}
