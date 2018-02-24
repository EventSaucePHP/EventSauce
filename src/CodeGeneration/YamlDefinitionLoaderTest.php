<?php

namespace EventSauce\EventSourcing\CodeGeneration;

use LogicException;
use PHPUnit\Framework\TestCase;
use const false;
use function file_get_contents;

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
        $definitionGroup = $loader->load(__DIR__ . '/Fixtures/exampleDefinition.yaml');
        $dumper = new CodeDumper();
        $code = $dumper->dump($definitionGroup);
        file_put_contents(__DIR__ . '/Fixtures/definedWithYamlFixture.php', $code);
        $expected = file_get_contents(__DIR__ . '/Fixtures/definedWithYamlFixture.php');
        $this->assertEquals($expected, $code);
    }

    /**
     * @test
     */
    public function loading_definitions_from_yaml_without_helpers()
    {
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load(__DIR__ . '/Fixtures/exampleDefinitionWithoutHelpers.yaml');
        $dumper = new CodeDumper();
        $code = $dumper->dump($definitionGroup, false);
        file_put_contents(__DIR__ . '/Fixtures/definedWithoutHelpersInYamlFixture.php', $code);
        $expected = file_get_contents(__DIR__ . '/Fixtures/definedWithoutHelpersInYamlFixture.php');
        $this->assertEquals($expected, $code);
    }

    /**
     * @test
     */
    public function loading_definitions_that_get_fields_from_other_types()
    {
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load(__DIR__ . '/Fixtures/definitionWithFieldsFromOtherDefinitions.yaml');
        $dumper = new CodeDumper();
        $code = $dumper->dump($definitionGroup, false);
        file_put_contents(__DIR__ . '/Fixtures/definitionWithFieldsFromOtherDefinitionsFixture.php', $code);
        $expected = file_get_contents(__DIR__ . '/Fixtures/definitionWithFieldsFromOtherDefinitionsFixture.php');
        $this->assertEquals($expected, $code);
    }

    /**
     * @test
     */
    public function trying_to_inherit_fields_from_unknown_type()
    {
        $this->expectException(LogicException::class);
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load(__DIR__ . '/Fixtures/inheritFieldsFromUnknownType.yaml');
        $dumper = new CodeDumper();
        $dumper->dump($definitionGroup, false);
    }
}