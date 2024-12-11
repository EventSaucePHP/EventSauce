<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher;

class AntiCorruptionMessageDispatcher implements MessageDispatcher
{
    private MessageFilter $filterBefore;
    private MessageFilter $filterAfter;
    private MessageTranslator $translator;

    public function __construct(
        private MessageDispatcher $dispatcher,
        ?MessageTranslator $translator = null,
        ?MessageFilter $filterBefore = null,
        ?MessageFilter $filterAfter = null,
    ) {
        $this->translator = $translator ?? new PassthroughMessageTranslator();
        $this->filterBefore = $filterBefore ?? new AllowAllMessages();
        $this->filterAfter = $filterAfter ?? new AllowAllMessages();
    }

    public function dispatch(Message ...$messages): void
    {
        $forwarded = [];

        foreach ($messages as $message) {
            if ( ! $this->filterBefore->allows($message)) {
                continue;
            }

            $message = $this->translator->translateMessage($message);

            if ( ! $this->filterAfter->allows($message)) {
                continue;
            }

            $forwarded[] = $message;
        }

        $this->dispatcher->dispatch(...$forwarded);
    }
}
