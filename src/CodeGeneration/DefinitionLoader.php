<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

interface DefinitionLoader
{
    public function canLoad(string $filename): bool;

    public function load(string $filename, DefinitionGroup $definitionGroup): DefinitionGroup;
}
