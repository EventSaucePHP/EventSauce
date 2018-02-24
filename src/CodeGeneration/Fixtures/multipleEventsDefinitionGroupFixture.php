<?php

namespace Multiple\Events\DefinitionGroup;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class FirstEvent implements Event
{
    /**
     * @var string
     */
    private $firstField;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording,
        string $firstField
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->firstField = $firstField;
    }

    public function firstField(): string
    {
        return $this->firstField;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new FirstEvent(
            $timeOfRecording,
            (string) $payload['firstField']
        );
    }

    public function toPayload(): array
    {
        return [
            'firstField' => (string) $this->firstField,
            '__event_version' => 1,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withFirstField(string $firstField): FirstEvent
    {
        $this->firstField = $firstField;

        return $this;
    }

    public static function with(PointInTime $timeOfRecording): FirstEvent
    {
        return new FirstEvent(
            $timeOfRecording,
            (string) 'FIRST'
        );
    }

}

final class SecondEvent implements Event
{
    /**
     * @var string
     */
    private $secondField;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording,
        string $secondField
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->secondField = $secondField;
    }

    public function secondField(): string
    {
        return $this->secondField;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new SecondEvent(
            $timeOfRecording,
            (string) $payload['secondField']
        );
    }

    public function toPayload(): array
    {
        return [
            'secondField' => (string) $this->secondField,
            '__event_version' => 1,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withSecondField(string $secondField): SecondEvent
    {
        $this->secondField = $secondField;

        return $this;
    }

    public static function with(PointInTime $timeOfRecording): SecondEvent
    {
        return new SecondEvent(
            $timeOfRecording,
            (string) 'SECOND'
        );
    }

}
