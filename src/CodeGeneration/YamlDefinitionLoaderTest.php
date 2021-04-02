<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use const false;
use InvalidArgumentException;
use LogicException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class YamlDefinitionLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_load_yaml_files(): void
    {
        $loader = new YamlDefinitionLoader();
        $this->assertTrue($loader->canLoad('a_yaml_file.yaml'));
        $this->assertTrue($loader->canLoad('a_yaml_file.yml'));
        $this->assertFalse($loader->canLoad('not_a_yaml_file.php'));
    }

    /**
     * @test
     * @dataProvider definitionProvider
     */
    public function generating_code_from_yaml(string $source, string $output, bool $withHelpers = true, bool $withSerializers = true): void
    {
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load($source);
        $dumper = new CodeDumper(false);
        $code = $dumper->dump($definitionGroup, $withHelpers, $withSerializers);
        file_put_contents($output, $code);
        $expected = file_get_contents($output);
        $this->assertEquals($expected, $code);
    }

    public function definitionProvider()
    {
        yield [__DIR__ . '/Fixtures/exampleDefinition.yaml', __DIR__ . '/Fixtures/definedWithYamlFixture.php'];
//        yield [__DIR__ . '/Fixtures/exampleDefinitionWithoutHelpers.yaml', __DIR__ . '/Fixtures/definedWithoutHelpersInYamlFixture.php', false];
//        yield [__DIR__ . '/Fixtures/definitionWithFieldsFromOtherDefinitions.yaml', __DIR__ . '/Fixtures/definitionWithFieldsFromOtherDefinitionsFixture.php', false];
//        yield [__DIR__ . '/Fixtures/commands-with-interfaces.yaml', __DIR__ . '/Fixtures/commandsWithInterfaces.php', false];
    }

    /**
     * @test
     */
    public function trying_to_inherit_fields_from_unknown_type(): void
    {
        $this->expectException(LogicException::class);
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load(__DIR__ . '/Fixtures/inheritFieldsFromUnknownType.yaml');
        $dumper = new CodeDumper(false);
        $dumper->dump($definitionGroup, false);
    }

    /**
     * @test
     */
    public function trying_to_use_non_defined_interfaces(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $loader = new YamlDefinitionLoader();
        $definitionGroup = $loader->load(__DIR__ . '/Fixtures/commands-with-non-existing-interfaces.yaml');
        $dumper = new CodeDumper(false);
        $dumper->dump($definitionGroup, false);
    }

    /**
     * @test
     */
    public function trying_to_use_non_existing_interfaces(): void
    {
        $this->expectException(LogicException::class);
        $loader = new YamlDefinitionLoader();
        $loader->load(__DIR__ . '/Fixtures/non-existing-interface.yaml');
    }

    /**
     * @test
     */
    public function loading_a_yaml_thats_not_an_array(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $loader = new YamlDefinitionLoader();
        $loader->load(__DIR__ . '/Fixtures/no-array.yml');
    }

    /**
     * @test
     */
    public function loading_a_yaml_that_does_not_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $loader = new YamlDefinitionLoader();
        $loader->load(__DIR__ . '/Fixtures/empty.yml');
    }
}
