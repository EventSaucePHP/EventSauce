<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class AntiCorruptionMessageConsumer implements MessageConsumer
{
    private MessageFilter $filterBefore;
    private MessageFilter $filterAfter;
    private MessageTranslator $translator;

    public function __construct(
        private MessageConsumer $consumer,
        ?MessageTranslator $translator = null,
        ?MessageFilter $filterBefore = null,
        ?MessageFilter $filterAfter = null,
    ) {
        $this->translator = $translator ?? new PassthroughMessageTranslator();
        $this->filterBefore = $filterBefore ?? new AllowAllMessages();
        $this->filterAfter = $filterAfter ?? new AllowAllMessages();
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

        $this->consumer->handle($message);
    }
}
