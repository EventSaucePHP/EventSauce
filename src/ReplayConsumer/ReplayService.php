<?php

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\MessageDispatcher;

class ReplayService
{
    public function __construct(
        protected ReplayMessageRepository $messageRepository,
        protected MessageDispatcher $dispatcher,
        protected $pageSize = 1000,
    )
    {
    }

    public function replay()
    {
        if ($this->dispatcher instanceof MessageDispatcherWithBeforeReplay) {
            $this->dispatcher->beforeReplay();
        }

        $offset = 0;
        while ($this->messageRepository->hasMessagesAfterOffset($offset)){
            $messages = $this->messageRepository->retrieveForReplayFromOffset($offset, $this->pageSize);
            $this->dispatcher->dispatch(...$messages);
            $offset = $messages->getReturn();
        }
    }
}