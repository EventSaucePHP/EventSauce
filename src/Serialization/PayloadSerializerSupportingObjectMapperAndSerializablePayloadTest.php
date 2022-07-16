<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\EventStub;
use PHPStan\Testing\TestCase;

class PayloadSerializerSupportingObjectMapperAndSerializablePayloadTest extends TestCase
{
    private PayloadSerializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = new PayloadSerializerSupportingObjectMapperAndSerializablePayload();
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

    /**
     * @test
     */
    public function serializes_serializable_event(): void
    {
        $event = EventStub::create('some value');
        $data = $this->serializer->serializePayload($event);

        $this->assertSame(['value' => 'some value'], $data);
    }

    /**
     * @test
     */
    public function unserialize_into_serializable_event(): void
    {
        /** @var EventStub $object */
        $object = $this->serializer->unserializePayload(EventStub::class, ['value' => 'some value']);

        $this->assertInstanceOf(EventStub::class, $object);
        $this->assertEquals('some value', $object->getValue());
    }
}
