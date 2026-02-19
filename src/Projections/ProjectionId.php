<?php

namespace EventSauce\EventSourcing\Projections;

class ProjectionId
{
    private function __construct(private string $id)
    {

    }
    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }
}
