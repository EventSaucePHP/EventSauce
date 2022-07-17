<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

abstract class EventConsumer implements MessageConsumer
{
    private HandleMethodInflector $handleMethodInflector;

    public function handle(Message $message): void
    {
        $this->handleMethodInflector ??= $this->handleMethodInflector();
        $methods = $this->handleMethodInflector->handleMethods($this, $message);

        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}($message->payload(), $message);
            }
        }
    }

    protected function handleMethodInflector(): HandleMethodInflector
    {
        return new InflectHandlerMethodsFromClassName();
    }
}
