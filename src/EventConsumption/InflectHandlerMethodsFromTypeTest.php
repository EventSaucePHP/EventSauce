<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\EventConsumption;

use EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption\DummyEventForConsuming;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class InflectHandlerMethodsFromTypeTest extends TestCase
{
    /** @test */
    public function it_inflects_the_method_name_from_the_event_type_and_available_methods(): void
    {
        $inflector = new InflectHandlerMethodsFromType();

        $names = $inflector->handleMethods(new TypedEventConsumerStub(), new Message(new DummyEventForConsuming('')));

        $this->assertContains('onDummyEvent', $names);
        $this->assertContains('shouldIncludeUnion', $names);
        $this->assertNotContains('shouldNotIncludeOtherEvent', $names);
        $this->assertNotContains('shouldNotIncludeProtectedMethod', $names);
        $this->assertNotContains('shouldNotIncludePrivateMethod', $names);
    }
}
