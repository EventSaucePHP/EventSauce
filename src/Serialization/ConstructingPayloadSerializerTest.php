<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\PayloadStub;
use PHPStan\Testing\TestCase;

final class ConstructingPayloadSerializerTest extends TestCase
{
    /**
     * @var ConstructingPayloadSerializer
     */
    private $serializer;

    public function setUp(): void
    {
        $this->serializer = new ConstructingPayloadSerializer();
    }

    /**
     * @test
     */
    public function serializes_serializable_event(): void
    {
        $event = PayloadStub::create('some value');
        $data = $this->serializer->serializePayload($event);

        $this->assertSame(['value' => 'some value'], $data);
    }

    /**
     * @test
     */
    public function unserialize_into_serializable_event(): void
    {
        /** @var PayloadStub $object */
        $object = $this->serializer->unserializePayload(PayloadStub::class, ['value' => 'some value']);

        $this->assertInstanceOf(PayloadStub::class, $object);
        $this->assertEquals('some value', $object->getValue());
    }
}
