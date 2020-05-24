<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingMessageConsumers;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use LogicException;

class TestedMessageConsumer implements MessageConsumer
{
    /**
     * @var int
     */
    private $failAfterNumberOfMessages;

    /**
     * @var int
     */
    private $numberOfMessagesProcessed = 0;

    public function __construct(int $failAfterNumberOfMessages)
    {
        $this->failAfterNumberOfMessages = $failAfterNumberOfMessages;
    }

    public function handle(Message $message): void
    {
        if (++$this->numberOfMessagesProcessed === $this->failAfterNumberOfMessages) {
            throw new LogicException('Too many messages');
        }
    }

    public function numberOfMessagesProcessed(): int
    {
        return $this->numberOfMessagesProcessed;
    }
}
