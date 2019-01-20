<?php

namespace EventSauce\EventSourcing\CodeGeneration;

use PHPUnit\Framework\TestCase;

class DefinitionGroupTest extends TestCase
{
    /**
     * @test
     */
    public function creating_a_definition_group()
    {
        $group = DefinitionGroup::create($namespace = 'Some\\Namespace');
        $this->assertEquals($namespace, $group->namespace());
    }
}