<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\SystemClock;

class DefaultHeadersDecorator implements MessageDecorator
{
    /**
     * @var ClassNameInflector
     */
    private $inflector;

    /**
     * @var Clock
     */
    private $clock;

    public function __construct(ClassNameInflector $inflector = null, Clock $clock = null)
    {
        $this->inflector = $inflector ?: new DotSeparatedSnakeCaseInflector();
        $this->clock = $clock ?: new SystemClock();
    }

    public function decorate(Message $message): Message
    {
        $event = $message->event();
        $headers = [
            Header::EVENT_TYPE        => $this->inflector->instanceToType($event),
            Header::TIME_OF_RECORDING => $this->clock->pointInTime()->toString(),
        ];

        return $message->withHeaders($headers);
    }
}
