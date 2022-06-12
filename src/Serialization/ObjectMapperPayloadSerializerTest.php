<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPUnit\Framework\TestCase;

class ObjectMapperPayloadSerializerTest extends TestCase
{
    private ObjectMapperPayloadSerializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = new ObjectMapperPayloadSerializer();
    }

    /**
     * @test
     */
    public function serializing_a_mapped_object(): void
    {
        $object = new MappedEventStub('some-value', 'Frank');

        $payload = $this->serializer->serializePayload($object);

        self::assertEquals(['value' => 'some-value', 'name' => 'Frank'], $payload);
    }

    /**
     * @test
     */
    public function unserializing_a_mapped_object(): void
    {
        $payload = ['value' => 'some-value', 'name' => 'Frank'];

        $object = $this->serializer->unserializePayload(MappedEventStub::class, $payload);

        self::assertInstanceOf(MappedEventStub::class, $object);
        self::assertEquals('some-value', $object->value);
        self::assertEquals('Frank', $object->name());
    }
}
