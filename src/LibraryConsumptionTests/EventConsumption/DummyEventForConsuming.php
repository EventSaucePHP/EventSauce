<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption;

/**
 * @testAsset
 */
class DummyEventForConsuming
{
    public function __construct(private string $message)
    {
    }

    public function message(): string
    {
        return $this->message;
    }
}
