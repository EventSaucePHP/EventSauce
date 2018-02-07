<?php

namespace Multiple\Events\DefinitionGroup;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class FirstEvent implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $firstField;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $firstField
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->firstField = $firstField;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function firstField(): string
    {
        return $this->firstField;
    }

    public function eventVersion(): int
    {
        return 1;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording): Event
    {
        return new FirstEvent(
            $aggregateRootId,
            $timeOfRecording,
            (string) $payload['firstField']
        );
    }

    public function toPayload(): array
    {
        return [
            'firstField' => (string) $this->firstField
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

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): FirstEvent
    {
        return new FirstEvent(
            $aggregateRootId,
            $timeOfRecording,
            (string) 'FIRST'
        );
    }

}

final class SecondEvent implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $secondField;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $secondField
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->secondField = $secondField;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function secondField(): string
    {
        return $this->secondField;
    }

    public function eventVersion(): int
    {
        return 1;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording): Event
    {
        return new SecondEvent(
            $aggregateRootId,
            $timeOfRecording,
            (string) $payload['secondField']
        );
    }

    public function toPayload(): array
    {
        return [
            'secondField' => (string) $this->secondField
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

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): SecondEvent
    {
        return new SecondEvent(
            $aggregateRootId,
            $timeOfRecording,
            (string) 'SECOND'
        );
    }

}
