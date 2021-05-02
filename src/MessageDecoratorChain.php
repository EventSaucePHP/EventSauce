<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class MessageDecoratorChain implements MessageDecorator
{
    /** @var MessageDecorator[] */
    private array $processors;

    public function __construct(MessageDecorator ...$processors)
    {
        $this->processors = $processors;
    }

    public function decorate(Message $message): Message
    {
        foreach ($this->processors as $processor) {
            $message = $processor->decorate($message);
        }

        return $message;
    }
}
