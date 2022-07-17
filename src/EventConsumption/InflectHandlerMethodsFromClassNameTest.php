<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\EventConsumption;

use EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption\DummyEventForConsuming;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class InflectHandlerMethodsFromClassNameTest extends TestCase
{
    /** @test */
    public function it_inflects_the_name_from_the_event_class_name(): void
    {
        $inflector = new InflectHandlerMethodsFromClassName();

        $names = $inflector->handleMethods(new \stdClass(), new Message(new DummyEventForConsuming('')));

        $this->assertContains('handleDummyEventForConsuming', $names);
    }
}
