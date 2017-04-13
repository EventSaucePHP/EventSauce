<?php

namespace EventSauce\EventSourcing\CodeGeneration;

use const false;
use PHPUnit\Framework\TestCase;

class YamlDefinitionLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function loading_definitions_from_yaml()
    {
        $loader = new YamlDefinitionLoader();
        $this->assertTrue($loader->canLoad('a_yaml_file.yaml'));
        $this->assertTrue($loader->canLoad('a_yaml_file.yml'));
        $this->assertFalse($loader->canLoad('not_a_yaml_file.php'));
        $definitionGroup = $loader->load(__DIR__.'/Fixtures/exampleDefinition.yaml');
        $dumper = new CodeDumper();
        $code = $dumper->dump($definitionGroup);
        $expected = file_get_contents(__DIR__.'/Fixtures/definedWithYamlFixture.php');
        $this->assertEquals($expected, $code);
    }

    /**
     * @test
     */
    public function loading_definitions_from_yaml_without_helpers()
    {
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load(__DIR__.'/Fixtures/exampleDefinitionWithoutHelpers.yaml');
        $dumper = new CodeDumper();
        $code = $dumper->dump($definitionGroup, false);
        $expected = file_get_contents(__DIR__.'/Fixtures/definedWithoutHelpersInYamlFixture.php');
        $this->assertEquals($expected, $code);
    }
}