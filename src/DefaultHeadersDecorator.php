<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\SystemClock;

class DefaultHeadersDecorator implements MessageDecorator
{
    private ClassNameInflector $inflector;
    private Clock $clock;

    public function __construct(ClassNameInflector $inflector = null, Clock $clock = null)
    {
        $this->inflector = $inflector ?: new DotSeparatedSnakeCaseInflector();
        $this->clock = $clock ?: new SystemClock();
    }

    public function decorate(Message $message): Message
    {
        return $message->withHeader(
            Header::EVENT_TYPE,
            $this->inflector->instanceToType($message->event())
        )->withTimeOfRecording($this->clock->now());
    }
}
