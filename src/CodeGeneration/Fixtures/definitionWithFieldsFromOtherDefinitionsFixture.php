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

    public function __construct(
        int $age
    ) {
        $this->age = $age;
    }

    public function age(): int
    {
        return $this->age;
    }

    public static function fromPayload(array $payload): Event
    {
        return new BaseEvent(
            (int) $payload['age']);
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

    public function __construct(
        int $age
    ) {
        $this->age = $age;
    }

    public function age(): int
    {
        return $this->age;
    }

    public static function fromPayload(array $payload): Event
    {
        return new ExtendedEvent(
            (int) $payload['age']);
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
     * @var string
     */
    private $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

}

final class ExtendedCommand
{
    /**
     * @var string
     */
    private $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

}
