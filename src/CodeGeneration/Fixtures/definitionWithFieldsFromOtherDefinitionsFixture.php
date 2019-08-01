<?php

declare(strict_types=1);

namespace EventsFrom\OtherTypes;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BaseEvent implements SerializablePayload
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

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new BaseEvent(
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

final class ExtendedEvent implements SerializablePayload
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

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new ExtendedEvent(
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

final class BaseCommand implements SerializablePayload
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

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new BaseCommand(
            (string) $payload['name']
        );
    }

    public function toPayload(): array
    {
        return [
            'name' => (string) $this->name,
        ];
    }
}

final class ExtendedCommand implements SerializablePayload
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

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new ExtendedCommand(
            (string) $payload['name']
        );
    }

    public function toPayload(): array
    {
        return [
            'name' => (string) $this->name,
        ];
    }
}
