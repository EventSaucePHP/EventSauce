<?php

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageConsumer;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageDispatcher;
use EventSauce\EventSourcing\CollectingMessageConsumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use PHPUnit\Framework\TestCase;

abstract class AntiCorruptionLayerTestCase extends TestCase
{
    private CollectingMessageConsumer $messageConsumer;

    /**
     * @var Message[]
     */
    private array $messagesToDispatch;

    public function setUp(): void
    {
        $this->messageConsumer = new CollectingMessageConsumer();
        parent::setUp();
    }

    protected function getDestinationDispatcher(): MessageDispatcher
    {
        return new SynchronousMessageDispatcher($this->messageConsumer);
    }

    protected function getDestinationConsumer(): MessageConsumer
    {
        return $this->messageConsumer;
    }

    protected function given(Message ...$messages): self
    {
        $this->messagesToDispatch = $messages;
        return $this;
    }

    protected function then(Message ...$messages): void
    {
        $messagesDispatched = $this->messageConsumer->collectedMessages();
        $this->assertCount(count($messages), $messagesDispatched);
        foreach ($messages as $index => $message) {
            $this->assertEquals($message, $messagesDispatched[$index]);
        }
    }

    protected function passedTroughAntiCorruptionMessageDispatcher(AntiCorruptionMessageDispatcher $dispatcher): self
    {
        $dispatcher->dispatch(...$this->messagesToDispatch);
        return $this;
    }

    protected function passedTroughAntiCorruptionMessageConsumer(AntiCorruptionMessageConsumer $consumer): self
    {
        foreach ($this->messagesToDispatch as $message) {
            $consumer->handle($message);
        }
        return $this;
    }
}
