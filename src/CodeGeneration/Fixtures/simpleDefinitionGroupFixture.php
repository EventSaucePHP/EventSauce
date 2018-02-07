<?php

namespace Simple\Definition\Group;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class SomethingHappened implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $what;

    /**
     * @var bool
     */
    private $yolo;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $what,
        bool $yolo
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->what = $what;
        $this->yolo = $yolo;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function what(): string
    {
        return $this->what;
    }

    public function yolo(): bool
    {
        return $this->yolo;
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
        return new SomethingHappened(
            $aggregateRootId,
            $timeOfRecording,
            (string) $payload['what'],
            (bool) $payload['yolo']
        );
    }

    public function toPayload(): array
    {
        return [
            'what' => (string) $this->what,
            'yolo' => (bool) $this->yolo
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withWhat(string $what): SomethingHappened
    {
        $this->what = $what;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withYolo(bool $yolo): SomethingHappened
    {
        $this->yolo = $yolo;

        return $this;
    }

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): SomethingHappened
    {
        return new SomethingHappened(
            $aggregateRootId,
            $timeOfRecording,
            (string) 'Example Event',
            (bool) true
        );
    }

}
