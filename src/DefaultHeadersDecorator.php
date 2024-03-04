<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\Clock\Clock;
use EventSauce\Clock\SystemClock;
use Psr\Clock\ClockInterface;

class DefaultHeadersDecorator implements MessageDecorator
{
    private ClassNameInflector $inflector;
    private Clock|ClockInterface $clock;
    private string $timeOfRecordingFormat;

    public function __construct(
        ClassNameInflector $inflector = null,
        Clock|ClockInterface $clock = null,
        string $timeOfRecordingFormat = Message::TIME_OF_RECORDING_FORMAT,
    ) {
        $this->inflector = $inflector ?: new DotSeparatedSnakeCaseInflector();
        $this->clock = $clock ?: new SystemClock();
        $this->timeOfRecordingFormat = $timeOfRecordingFormat;
    }

    public function decorate(Message $message): Message
    {
        return $message->withHeader(
            Header::EVENT_TYPE,
            $this->inflector->instanceToType($message->payload())
        )->withTimeOfRecording($this->clock->now(), $this->timeOfRecordingFormat);
    }
}
