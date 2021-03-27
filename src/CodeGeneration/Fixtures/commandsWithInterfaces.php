<?php

declare(strict_types=1);

namespace CommandsWithInterfaces;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class EventWithInterfaceMarker implements \EventSauce\EventSourcing\CodeGeneration\Fixtures\MarkerInterfaceStub, SerializablePayload
{
    public static function fromPayload(array $payload): self
    {
        return new EventWithInterfaceMarker();
    }

    public function toPayload(): array
    {
        return [];
    }
}

final class CommandWithInterfaceMarker implements \EventSauce\EventSourcing\CodeGeneration\Fixtures\MarkerInterfaceStub, SerializablePayload
{
    public static function fromPayload(array $payload): self
    {
        return new CommandWithInterfaceMarker();
    }

    public function toPayload(): array
    {
        return [];
    }
}

final class AlsoCommandWithInterfaceMarker implements \EventSauce\EventSourcing\CodeGeneration\Fixtures\MarkerInterfaceStub, SerializablePayload
{
    public static function fromPayload(array $payload): self
    {
        return new AlsoCommandWithInterfaceMarker();
    }

    public function toPayload(): array
    {
        return [];
    }
}
