<?php

namespace EventsFrom\OtherTypes;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class BaseEvent implements Event
{
    /**
     * @var int
     */
    private $age;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording,
        int $age
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->age = $age;
    }

    public function age(): int
    {
        return $this->age;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new BaseEvent(
            $timeOfRecording,
            (int) $payload['age']
        );
    }

    public function toPayload(): array
    {
        return [
            'age' => (int) $this->age,
        ];
    }

}

final class ExtendedEvent implements Event
{
    /**
     * @var int
     */
    private $age;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording,
        int $age
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->age = $age;
    }

    public function age(): int
    {
        return $this->age;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new ExtendedEvent(
            $timeOfRecording,
            (int) $payload['age']
        );
    }

    public function toPayload(): array
    {
        return [
            'age' => (int) $this->age,
        ];
    }

}

final class BaseCommand
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        PointInTime $timeOfRequest,
        string $name
    ) {
        $this->timeOfRequest = $timeOfRequest;
        $this->name = $name;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function name(): string
    {
        return $this->name;
    }

}

final class ExtendedCommand
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        PointInTime $timeOfRequest,
        string $name
    ) {
        $this->timeOfRequest = $timeOfRequest;
        $this->name = $name;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function name(): string
    {
        return $this->name;
    }

}
