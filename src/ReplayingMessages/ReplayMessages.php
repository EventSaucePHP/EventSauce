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

    public function replayBatch(int $pageSize, ?PaginationCursor $cursor = null): ReplayResult
    {
        if ($cursor === null && $this->consumer instanceof TriggerBeforeReplay) {
            $this->consumer->beforeReplay();
        }

        $messagesHandled = 0;
        $messages = $this->repository->paginate($pageSize, $cursor);

        foreach ($messages as $message) {
            $this->consumer->handle($message);
            $messagesHandled++;
        }

        if ($messagesHandled === 0 && $this->consumer instanceof TriggerAfterReplay) {
            $this->consumer->afterReplay();
        }

        return new ReplayResult($messagesHandled, $messages->getReturn());
    }
}
