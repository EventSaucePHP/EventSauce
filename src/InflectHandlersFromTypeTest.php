<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption\DummyEventForConsuming;
use PHPUnit\Framework\TestCase;

class InflectHandlersFromTypeTest extends TestCase
{
    /** @test */
    public function it_inflects_the_method_name_from_the_event_type_and_available_methods(): void
    {
        $inflector = new InflectHandlersFromType();

        $names = $inflector->getMethodNames(new TypedEventConsumer(), new Message(new DummyEventForConsuming('')));

        $this->assertContains('onDummyEvent', $names);
        $this->assertContains('shouldIncludeUnion', $names);
        $this->assertNotContains('shouldNotIncludeOtherEvent', $names);
        $this->assertNotContains('shouldNotIncludeProtectedMethod', $names);
        $this->assertNotContains('shouldNotIncludePrivateMethod', $names);
    }
}

class TypedEventConsumer extends EventConsumer
{
    public function onDummyEvent(DummyEventForConsuming $event): void
    {
    }

    public function shouldIncludeUnion(DummyEventForConsuming|DummyEvent $event): void
    {
    }

    public function shouldNotIncludeOtherEvent(DummyEvent $event): void
    {
    }

    private function shouldNotIncludeProtectedMethod(DummyEventForConsuming $event): void
    {
    }

    private function shouldNotIncludePrivateMethod(DummyEventForConsuming $event): void
    {
    }
}
