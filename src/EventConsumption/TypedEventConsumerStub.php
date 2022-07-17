<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\EventConsumption;

use EventSauce\EventSourcing\DummyEvent;
use EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption\DummyEventForConsuming;

class TypedEventConsumerStub extends EventConsumer
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
