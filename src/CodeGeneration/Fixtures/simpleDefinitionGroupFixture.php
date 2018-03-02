<?php

namespace Simple\Definition\Group;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class SomethingHappened implements Event
{
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
        PointInTime $timeOfRecording,
        string $what,
        bool $yolo
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->what = $what;
        $this->yolo = $yolo;
    }

    public function what(): string
    {
        return $this->what;
    }

    public function yolo(): bool
    {
        return $this->yolo;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new SomethingHappened(
            $timeOfRecording,
            (string) $payload['what'],
            (bool) $payload['yolo']
        );
    }

    public function toPayload(): array
    {
        return [
            'what' => (string) $this->what,
            'yolo' => (bool) $this->yolo,
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

    public static function with(PointInTime $timeOfRecording): SomethingHappened
    {
        return new SomethingHappened(
            $timeOfRecording,
            (string) 'Example Event',
            (bool) true
        );
    }

}
