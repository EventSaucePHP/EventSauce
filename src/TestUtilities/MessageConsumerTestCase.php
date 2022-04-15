<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Exception;
use PHPUnit\Framework\TestCase;

abstract class MessageConsumerTestCase extends TestCase
{
    protected MessageConsumer $messageConsumer;
    private ?Exception $caughtException = null;
    private ?Exception $theExpectedException = null;
    private bool $assertedScenario = false;

    /**
     * @var callable
     */
    private $assertionCallback;
    private ?AggregateRootId $aggregateRootId = null;

    abstract public function messageConsumer(): MessageConsumer;

    /**
     * @before
     */
    public function setupMessageConsumer(): void
    {
        $this->messageConsumer = $this->messageConsumer();
        $this->assertedScenario = false;
    }

    /**
     * @before
     */
    public function unsetMessageConsumer(): void
    {
        unset($this->messageConsumer);
    }

    /**
     * @return $this
     */
    protected function given(object ...$eventsOrMessages)
    {
        $this->processMessages($eventsOrMessages);

        return $this;
    }

    /**
     * @return $this
     */
    public function when(object ...$eventsOrMessages)
    {
        try {
            $this->processMessages($eventsOrMessages);
        } catch (Exception $exception) {
            $this->caughtException = $exception;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function expectToFail(Exception $expectedException)
    {
        $this->theExpectedException = $expectedException;

        return $this;
    }

    public function givenNextMessagesHaveAggregateRootIdOf(?AggregateRootId $aggregateRootId): self
    {
        $this->aggregateRootId = $aggregateRootId;
        return $this;
    }

    /**
     * @param object[] $eventsOrMessages
     */
    protected function processMessages(array $eventsOrMessages): void
    {
        $messages = $this->ensureEventsAreMessages($eventsOrMessages);
        if (isset($this->aggregateRootId)) {
            $messages = $this->ensureEventsHaveUuid($messages, $this->aggregateRootId);
        }

        foreach ($messages as $message) {
            $this->messageConsumer->handle($message);
        }
    }

    /**
     * @return Message[]
     */
    private function ensureEventsAreMessages(array $events): array
    {
        return array_map(function (object $event) {
            return $event instanceof Message ? $event : new Message($event);
        }, $events);
    }

    /**
     * @return Message[]
     */
    private function ensureEventsHaveUuid(array $messages, AggregateRootId $aggregateRootId): array
    {
        return array_map(function (Message $message) use ($aggregateRootId) {
            return $message->withHeaders([
                Header::AGGREGATE_ROOT_ID => $aggregateRootId,
            ]);
        }, $messages);
    }

    /**
     * @after
     *
     * @throws Exception
     */
    protected function assertScenario(): void
    {
        // @codeCoverageIgnoreStart
        if ($this->assertedScenario) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $this->assertedScenario = true;

        $this->assertExpectedException($this->theExpectedException, $this->caughtException);

        if (is_callable($this->assertionCallback)) {
            ($this->assertionCallback)($this->messageConsumer);
        }
    }

    /**
     * @return $this
     */
    public function then(callable $assertion)
    {
        $this->assertionCallback = $assertion;

        return $this;
    }

    private function assertExpectedException(
        Exception $expectedException = null,
        Exception $caughtException = null
    ): void {
        if ($caughtException !== null && ($expectedException === null || get_class($expectedException) !== get_class(
                    $caughtException
                ))) {
            throw $caughtException;
        }

        self::assertEquals([$expectedException], [$caughtException], '>> Exceptions are not equal.');
    }
}
