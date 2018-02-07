<?php

namespace EventsFrom\OtherTypes;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class BaseEvent implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var int
     */
    private $age;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        int $age
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->age = $age;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function age(): int
    {
        return $this->age;
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
        return new BaseEvent(
            $aggregateRootId,
            $timeOfRecording,
            (int) $payload['age']
        );
    }

    public function toPayload(): array
    {
        return [
            'age' => (int) $this->age
        ];
    }

}

final class ExtendedEvent implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var int
     */
    private $age;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        int $age
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->age = $age;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function age(): int
    {
        return $this->age;
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
        return new ExtendedEvent(
            $aggregateRootId,
            $timeOfRecording,
            (int) $payload['age']
        );
    }

    public function toPayload(): array
    {
        return [
            'age' => (int) $this->age
        ];
    }

}

final class BaseCommand implements Command
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRequest,
        string $name
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRequest = $timeOfRequest;
        $this->name = $name;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function name(): string
    {
        return $this->name;
    }

}

final class ExtendedCommand implements Command
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRequest,
        string $name
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRequest = $timeOfRequest;
        $this->name = $name;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function name(): string
    {
        return $this->name;
    }

}
