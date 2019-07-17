<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use PHPStan\Testing\TestCase;
use EventSauce\EventSourcing\PayloadStub;

final class ConstructingPayloadSerializerTest extends TestCase
{
    private $serializer;

    public function setUp()
    {
        $this->serializer = new ConstructingPayloadSerializer();
    }

    /**
     * @test
     */
    public function serializes_serializable_event()
    {
        $event = PayloadStub::create('some value');
        $data = $this->serializer->serializeEvent($event);

        $this->assertSame(['value' => 'some value'], $data);
    }

    /**
     * @test
     */
    public function unserialize_into_serializable_event()
    {
        $object = $this->serializer->unserializePayload(PayloadStub::class, ['value' => 'some value']);

        $this->assertInstanceOf(PayloadStub::class, $object);
        $this->assertAttributeSame('some value', 'value', $object);
    }
}
