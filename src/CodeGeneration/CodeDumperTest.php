<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use function file_get_contents;
use PHPUnit\Framework\TestCase;

class CodeDumperTest extends TestCase
{
    /**
     * @test
     * @dataProvider definitionProvider
     */
    public function dumping_a_definition(DefinitionGroup $definitionGroup, string $fixtureFile): void
    {
        $dumper = new CodeDumper();
        $actual = $dumper->dump($definitionGroup);
        // file_put_contents(__DIR__ . '/Fixtures/' . $fixtureFile . 'Fixture.php', $actual);
        $expected = file_get_contents(__DIR__ . '/Fixtures/' . $fixtureFile . 'Fixture.php');
        $this->assertEquals($expected, $actual, "Expect {$fixtureFile} to match generated code.");
    }

    public function definitionProvider()
    {
        /* test case 1 */
        $simpleDefinitionGroup = DefinitionGroup::create('Simple\\Definition\\Group');
        $simpleDefinitionGroup->event('SomethingHappened')
            ->field('what', 'string', 'Example Event')
            ->field('yolo', 'bool', 'true');

        /* test case 2 */
        $multipleEventsDefinitionGroup = DefinitionGroup::create('Multiple\\Events\\DefinitionGroup');
        $multipleEventsDefinitionGroup->event('FirstEvent')
            ->field('firstField', 'string', 'FIRST');
        $multipleEventsDefinitionGroup->event('SecondEvent')
            ->field('secondField', 'string', 'SECOND');

        /* test case 3 */
        $definitionGroupWithDefaults = DefinitionGroup::create('Group\\With\\Defaults');
        $definitionGroupWithDefaults->fieldDefault('description', 'string', 'This is a description.');
        $definitionGroupWithDefaults->event('EventWithDescription')
            ->field('description', 'string');

        /* test case 4 */
        $groupWithFieldSerialization = DefinitionGroup::create('Group\\With\\FieldDeserialization');
        $groupWithFieldSerialization->fieldSerializer('items', <<<EOF
array_map(function (\$item) {
    return \$item['property'];
}, {param})
EOF
        );
        $groupWithFieldSerialization->fieldDeserializer('items', <<<EOF
array_map(function (\$property) {
    return ['property' => \$property];
}, {param})
EOF
        );
        $groupWithFieldSerialization->event('WithFieldSerializers')
            ->field('items', 'array');

        /* test case 5 */
        $definitionGroupWithCommand = DefinitionGroup::create('With\Commands');
        $definitionGroupWithCommand->command('DoSomething')
            ->field('reason', 'string', 'Because reasons.');

        /* test case 6 */
        $groupWithFieldSerializationFromEvent = DefinitionGroup::create('With\\EventFieldSerialization');
        $groupWithFieldSerializationFromEvent->event('EventName')
            ->field('title', 'string', 'Title')
            ->fieldSerializer('title', <<<EOF
strtoupper({param})
EOF
            )->fieldDeserializer('title', <<<EOF
strtolower({param})
EOF
            );

        $groupWithEventWithTwoRequiredFields = DefinitionGroup::create('With\\ManyRequiredFields');
        $groupWithEventWithTwoRequiredFields->event('ThisOne')
            ->field('title', 'string')
            ->field('description', 'string');

        $groupWithEventWithNoFields = DefinitionGroup::create('Without\Fields');
        $groupWithEventWithNoFields->event('WithoutFields');

        return [
            [$simpleDefinitionGroup, 'simpleDefinitionGroup'],
            [$multipleEventsDefinitionGroup, 'multipleEventsDefinitionGroup'],
            [$definitionGroupWithDefaults, 'definitionGroupWithDefaults'],
            [$groupWithFieldSerialization, 'groupWithFieldSerialization'],
            [$definitionGroupWithCommand, 'definitionGroupWithCommand'],
            [$groupWithFieldSerializationFromEvent, 'groupWithFieldSerializationFromEvent'],
            [$groupWithEventWithTwoRequiredFields, 'groupWithEventWithTwoRequiredFields'],
            [$groupWithEventWithNoFields, 'groupWithEventWithNoFields'],
        ];
    }
}
