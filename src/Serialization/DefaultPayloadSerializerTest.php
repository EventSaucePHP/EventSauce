<?php
declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use LogicException;
use PHPUnit\Framework\TestCase;
use function putenv;

class DefaultPayloadSerializerTest extends TestCase
{
    /**
     * @before
     */
    public function clearEnvAndDefaultSerializer(): void
    {
        putenv('EVENTSAUCE_DEFAULT_SERIALIZER');
        DefaultPayloadSerializer::dangerouslyUnsetDefaultPayloadSerializer();
    }

    /**
     * @test
     */
    public function it_resolves_a_default_serializer(): void
    {
        $serializer = DefaultPayloadSerializer::resolve();

        self::assertInstanceOf(PayloadSerializer::class, $serializer);
        self::assertInstanceOf(ConstructingPayloadSerializer::class, $serializer);
    }

    /**
     * @test
     */
    public function when_env_is_set_it_returns_a_object_mapper_serializer(): void
    {
        putenv('EVENTSAUCE_DEFAULT_SERIALIZER=object-mapper');

        $serializer = DefaultPayloadSerializer::resolve();

        self::assertInstanceOf(ObjectMapperPayloadSerializer::class, $serializer);
    }

    /**
     * @test
     */
    public function you_can_specify_which_payload_serializer_to_use(): void
    {
        $serializer = new ObjectMapperPayloadSerializer();
        DefaultPayloadSerializer::usePayloadSerializer($serializer);

        self::assertEquals($serializer, DefaultPayloadSerializer::resolve());
    }

    /**
     * @test
     */
    public function it_rejects_specifying_a_payload_serlializer_when_one_it_already_resolved(): void
    {
        DefaultPayloadSerializer::resolve();

        $this->expectException(LogicException::class);

        DefaultPayloadSerializer::usePayloadSerializer(new ConstructingPayloadSerializer());
    }
}
