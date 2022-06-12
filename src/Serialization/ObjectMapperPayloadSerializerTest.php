<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPUnit\Framework\TestCase;

class ObjectMapperPayloadSerializerTest extends TestCase
{
    private function serializer(): PayloadSerializer
    {
        return new ObjectMapperPayloadSerializer();
    }

    /**
     * @test
     */
    public function serializing_a_mapped_object(): void
    {
        $serializer = $this->serializer();
        $object = new MappedEventStub('some-value', 'Frank');

        $payload = $serializer->serializePayload($object);

        self::assertEquals(['value' => 'some-value', 'name' => 'Frank'], $payload);
    }

    /**
     * @test
     */
    public function unserializing_a_mapped_object(): void
    {
        $serializer = $this->serializer();
        $payload = ['value' => 'some-value', 'name' => 'Frank'];

        $object = $serializer->unserializePayload(MappedEventStub::class, $payload);

        self::assertInstanceOf(MappedEventStub::class, $object);
        self::assertEquals('some-value', $object->value);
        self::assertEquals('Frank', $object->name());
    }
}
