<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher;

class AntiCorruptionMessageRelay implements MessageDispatcher
{
    private MessageFilter $filterBefore;
    private MessageFilter $filterAfter;

    public function __construct(
        private MessageDispatcher $dispatcher,
        private MessageTranslator $translator,
        MessageFilter $filterBefore = null,
        MessageFilter $filterAfter = null,
    )
    {
        $this->filterBefore = $filterBefore ?? new AlwaysAllowingMessageFilter();
        $this->filterAfter = $filterAfter ?? new AlwaysAllowingMessageFilter();
    }

    public function dispatch(Message ...$messages): void
    {
        $forwarded = [];

        foreach ($messages as $message) {
            if ( ! $this->filterBefore->allows($message)) {
                return;
            }

            $message = $this->translator->translateMessage($message);

            if ( ! $this->filterAfter->allows($message)) {
                return;
            }

            $forwarded[] = $message;
        }

        $this->dispatcher->dispatch(...$forwarded);
    }
}
