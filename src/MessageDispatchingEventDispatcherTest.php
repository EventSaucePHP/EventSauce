<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

class MessageDispatchingEventDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function dispatching_messages_plainly(): void
    {
        $subdispatcher = new CollectingMessageDispatcher();
        $eventDispatcher = new MessageDispatchingEventDispatcher($subdispatcher);
        $event = new EventStub('value');
        $eventDispatcher->dispatch($event);
        $collectedMessages = $subdispatcher->collectedMessages();
        $this->assertEquals($event, $collectedMessages[0]->event());
    }

    /**
     * @test
     */
    public function dispatching_with_headers(): void
    {
        $subdispatcher = new CollectingMessageDispatcher();
        $eventDispatcher = new MessageDispatchingEventDispatcher($subdispatcher);
        $event = new EventStub('value');
        $eventDispatcher->dispatchWithHeaders(['some_header' => 'some_value'], $event);
        $collectedMessages = $subdispatcher->collectedMessages();
        $this->assertEquals('some_value', $collectedMessages[0]->header('some_header'));
    }
}
