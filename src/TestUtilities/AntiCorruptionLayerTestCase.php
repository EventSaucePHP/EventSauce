<?php

namespace EventSauce\EventSourcing\TestUtilities;

use Closure;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageConsumer;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageDispatcher;
use EventSauce\EventSourcing\AntiCorruptionLayer\PassthroughMessageTranslator;
use EventSauce\EventSourcing\CollectingMessageConsumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use PHPUnit\Framework\TestCase;
use function array_map;

abstract class AntiCorruptionLayerTestCase extends TestCase
{
    /**
     * @var Message[]
     */
    private array $messagesToDispatch;

    private Closure $dispatcher;
    private Closure $consumer;

    public function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = fn(MessageDispatcher $dispatcher) => $this->antiCorruptionDispatcher($dispatcher);
        $this->consumer = fn(MessageConsumer $consumer) => $this->antiCorruptionConsumer($consumer);
    }

    protected function antiCorruptionDispatcher(MessageDispatcher $dispatcher): AntiCorruptionMessageDispatcher
    {
        return new AntiCorruptionMessageDispatcher(
            $dispatcher,
            new PassthroughMessageTranslator()
        );
    }

    protected function antiCorruptionConsumer(MessageConsumer $consumer): AntiCorruptionMessageConsumer
    {
        return new AntiCorruptionMessageConsumer(
            $consumer,
            new PassthroughMessageTranslator()
        );
    }

    protected function givenMessages(Message ...$messages): self
    {
        $this->messagesToDispatch = $messages;

        return $this;
    }

    protected function givenEvents(object ...$events): self
    {
        return $this->givenMessages(
            ...array_map(
                fn(object $object) => new Message($object),
                $events
            )
        );
    }

    protected function expectNoMessages(): void
    {
        $this->expectMessages();
    }

    protected function expectNoEvents(): void
    {
        $this->expectEvents();
    }

    protected function expectMessages(Message ...$messages): void
    {
        $consumer = new CollectingMessageConsumer();
        $aclConsumer = ($this->consumer)($consumer);
        $dispatcher = new SynchronousMessageDispatcher($aclConsumer);
        $aclDispatcher = ($this->dispatcher)($dispatcher);

        $aclDispatcher->dispatch(...$this->messagesToDispatch);
        $messagesDispatched = $consumer->collectedMessages();
        $this->assertCount(count($messages), $messagesDispatched);

        foreach ($messages as $index => $message) {
            $this->assertEquals($message, $messagesDispatched[$index] ?? null);
        }
    }

    protected function expectEvents(object ...$objects): void
    {
        $messages = array_map(
            fn(object $object) => new Message($object),
            $objects
        );

        $this->expectMessages(...$messages);
    }

    protected function dispatchedThrough(Closure $dispatcher): self
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    protected function consumedThrough(Closure $consumer): self
    {
        $this->consumer = $consumer;

        return $this;
    }
}
