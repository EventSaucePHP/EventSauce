<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

class MappedEventStub
{
    public string $value;
    private string $name;

    public function __construct(string $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
