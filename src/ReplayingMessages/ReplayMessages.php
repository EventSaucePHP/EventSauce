<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayingMessages;

use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\PaginationCursor;

class ReplayMessages
{
    public function __construct(
        private MessageRepository $repository,
        private MessageConsumer $consumer,
    ) {
    }

    public function replayBatch(PaginationCursor $cursor): ReplayResult
    {
        if ($cursor->isAtStart() && $this->consumer instanceof TriggerBeforeReplay) {
            $this->consumer->beforeReplay();
        }

        $messagesHandled = 0;
        $messages = $this->repository->paginate($cursor);

        foreach ($messages as $message) {
            $this->consumer->handle($message);
            ++$messagesHandled;
        }

        if ($messagesHandled === 0 && $this->consumer instanceof TriggerAfterReplay) {
            $this->consumer->afterReplay();
        }

        return new ReplayResult($messagesHandled, $messages->getReturn());
    }
}
