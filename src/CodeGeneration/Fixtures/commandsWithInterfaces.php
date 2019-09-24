<?php

declare(strict_types=1);

namespace CommandsWithInterfaces;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class EventWithInterfaceMarker implements \EventSauce\EventSourcing\CodeGeneration\Fixtures\MarkerInterfaceStub, SerializablePayload
{
    public static function fromPayload(array $payload): SerializablePayload
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
    public static function fromPayload(array $payload): SerializablePayload
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
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new AlsoCommandWithInterfaceMarker();
    }

    public function toPayload(): array
    {
        return [];
    }
}
