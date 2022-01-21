<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\MessageDispatcher;

class ReplayService
{
    public function __construct(
        protected ReplayMessageRepository $messageRepository,
        protected MessageDispatcher $dispatcher,
        protected int $pageSize = 1000,
    ) {
    }

    public function replay(): void
    {
        if ($this->dispatcher instanceof MessageDispatcherWithBeforeReplay) {
            $this->dispatcher->beforeReplay();
        }

        $offset = 0;
        while ($this->messageRepository->hasMessagesAfterOffset($offset)) {
            $messages = $this->messageRepository->retrieveForReplayFromOffset($offset, $this->pageSize);
            $this->dispatcher->dispatch(...$messages);
            $offset = $messages->getReturn();
        }
    }
}
