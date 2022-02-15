<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageDispatcher;

class AntiCorruptionMessageRelay implements MessageConsumer
{
    private MessageFilter $filterBefore;
    private MessageFilter $filterAfter;

    public function __construct(
        private MessageTranslator $translator,
        private MessageDispatcher $dispatcher,
        MessageFilter $filterBefore = null,
        MessageFilter $filterAfter = null,
    )
    {
        $this->filterBefore = $filterBefore ?? new AlwaysAllowingMessageFilter();
        $this->filterAfter = $filterAfter ?? new AlwaysAllowingMessageFilter();
    }

    public function handle(Message $message): void
    {
        if ( ! $this->filterBefore->allows($message)) {
            return;
        }

        $message = $this->translator->translateMessage($message);

        if ( ! $this->filterAfter->allows($message)) {
            return;
        }

        $this->dispatcher->dispatch($message);
    }
}
