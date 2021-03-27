<?php

declare(strict_types=1);

namespace EventsFrom\OtherTypes;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class BaseEvent implements SerializablePayload
{
    public function __construct(
        private int $age
    ) {
    }

    public function age(): int
    {
        return $this->age;
    }

    public static function fromPayload(array $payload): self
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
    public function __construct(
        private int $age
    ) {
    }

    public function age(): int
    {
        return $this->age;
    }

    public static function fromPayload(array $payload): self
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
    public function __construct(
        private string $name
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function fromPayload(array $payload): self
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
    public function __construct(
        private string $name
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function fromPayload(array $payload): self
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
