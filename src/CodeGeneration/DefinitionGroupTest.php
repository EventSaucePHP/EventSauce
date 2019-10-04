<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use PHPUnit\Framework\TestCase;

class DefinitionGroupTest extends TestCase
{
    /**
     * @test
     */
    public function creating_a_definition_group(): void
    {
        $group = DefinitionGroup::create($namespace = 'Some\\Namespace');
        $this->assertEquals($namespace, $group->namespace());
    }
}
