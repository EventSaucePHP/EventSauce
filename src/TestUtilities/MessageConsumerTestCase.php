<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use Exception;
use PHPUnit\Framework\TestCase;

abstract class MessageConsumerTestCase extends TestCase
{
    /**
     * @var MessageConsumer
     */
    protected $messageConsumer;

    /**
     * @var Exception|null
     */
    private $caughtException;

    /**
     * @var Exception|null
     */
    private $theExpectedException;

    /**
     * @var bool
     */
    private $assertedScenario = false;

    /**
     * @var callable
     */
    private $assertionCallback;

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
    protected function given(... $eventsOrMessages)
    {
        $this->processMessages($eventsOrMessages);

        return $this;
    }

    /**
     * @return $this
     */
    public function when(... $eventsOrMessages)
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

    /**
     * @param array $eventsOrMessages
     */
    protected function processMessages(array $eventsOrMessages): void
    {
        $messages = $this->ensureEventsAreMessages($eventsOrMessages);

        foreach ($messages as $message) {
            $this->messageConsumer->handle($message);
        }
    }

    /**
     * @return Message[]
     */
    private function ensureEventsAreMessages(array $events): array
    {
        return array_map(function(object $event) {
            return $event instanceof Message ? $event : new Message($event);
        }, $events);
    }

    /**
     * @after
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
        if (null !== $caughtException && (null === $expectedException || get_class($expectedException) !== get_class(
                    $caughtException
                ))) {
            throw $caughtException;
        }

        self::assertEquals([$expectedException], [$caughtException], '>> Exceptions are not equal.');
    }
}
