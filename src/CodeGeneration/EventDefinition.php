<?php

namespace EventSauce\EventSourcing\CodeGeneration;

final class EventDefinition extends DefinitionWithFields
{
    /**
     * @var int
     */
    private $version = 1;

    public function version(): int
    {
        return $this->version;
    }

    public function atVersion(int $version)
    {
        $this->version = $version;

        return $this;
    }
}