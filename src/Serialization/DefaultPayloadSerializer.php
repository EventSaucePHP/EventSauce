<?php
declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use LogicException;
use function getenv;

/**
 * @interal
 */
final class DefaultPayloadSerializer
{
    private static ?PayloadSerializer $serializer = null;

    private static function createSerializer(): PayloadSerializer
    {
        return match (getenv('EVENTSAUCE_DEFAULT_SERIALIZER')) {
            'object-mapper' => new ObjectMapperPayloadSerializer(),
            'payload-serializer' => new ConstructingPayloadSerializer(),
            default => new PayloadSerializerSupportingObjectMapperAndSerializablePayload(),
        };
    }

    public static function resolve(): PayloadSerializer
    {
        return static::$serializer ??= static::createSerializer();
    }

    public static function usePayloadSerializer(PayloadSerializer $payloadSerializer): void
    {
        if (static::$serializer instanceof PayloadSerializer) {
            throw new LogicException('Payload serializer was already set or resolved, it can only be set once before resolving');
        }

        static::$serializer = $payloadSerializer;
    }

    public static function dangerouslyUnsetDefaultPayloadSerializer(): void
    {
        static::$serializer = null;
    }
}
