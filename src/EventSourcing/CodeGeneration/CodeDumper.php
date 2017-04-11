<?php

namespace EventSauce\EventSourcing\CodeGeneration;

use function join;
use const null;
use function sprintf;
use function ucfirst;
use function var_export;

class CodeDumper
{
    public function dump(DefinitionGroup $definitionGroup): string
    {
        $eventsCode = $this->dumpEvents($definitionGroup->events());
        $commandCode = $this->dumpCommands($definitionGroup->commands());
        $namespace = $definitionGroup->namespace();

        return <<<EOF
<?php

namespace $namespace;

use EventSauce\\EventSourcing\\AggregateRootId;
use EventSauce\\EventSourcing\\Command;
use EventSauce\\EventSourcing\\Event;
use EventSauce\\EventSourcing\\PointInTime;

$eventsCode
$commandCode
EOF;
    }

    private function dumpEvents(array $events): string
    {
        $code = [];

        foreach ($events as $event) {
            $name = $event->name();
            $fields = $this->dumpFields($event);
            $constructor = $this->dumpEventConstructor($event);
            $methods = $this->dumpMethods($event);
            $deserializer = $this->dumpSerializationMethods($event);
            $testHelpers = $this->dumpTestHelpers($event);

            $code[] = <<<EOF
final class $name implements Event
{
$fields    /**
     * @var PointInTime
     */
    private \$timeOfRecording;

$constructor$methods    public function eventVersion(): int
    {
        return {$event->version()};
    }
    
    public function timeOfRecording(): PointInTime
    {
        return \$this->timeOfRecording;
    }

$deserializer

$testHelpers}


EOF;
        }

        return join('', $code);
    }

    private function dumpFields(DefinitionWithFields $definition): string
    {
        $code = [];
        $code[] = <<<EOF
    /**
     * @var AggregateRootId
     */
    private \$aggregateRootId;


EOF;


        foreach ($definition->fields() as $field) {
            $name = $field['name'];
            $type = $field['type'];

            $code[] = <<<EOF
    /**
     * @var $type
     */
    private \$$name;


EOF;

        }

        return join('', $code);
    }

    private function dumpEventConstructor(EventDefinition $event): string
    {
        $arguments = ['        AggregateRootId $aggregateRootId', '        PointInTime $timeOfRecording'];
        $assignments = ['        $this->aggregateRootId = $aggregateRootId;', '        $this->timeOfRecording = $timeOfRecording;'];

        foreach ($event->fields() as $field) {
            $arguments[] = sprintf('        %s $%s', $field['type'], $field['name']);
            $assignments[] = sprintf('        $this->%s = $%s;', $field['name'], $field['name']);
        }

        $arguments = join(",\n", $arguments);
        $assignments = join("\n", $assignments);



        return <<<EOF
    public function __construct(
$arguments
    ) {
$assignments
    }


EOF;

    }

    private function dumpCommandConstructor(CommandDefinition $command): string
    {
        $arguments = ['        AggregateRootId $aggregateRootId', '        PointInTime $timeOfRequest'];
        $assignments = ['        $this->aggregateRootId = $aggregateRootId;', '        $this->timeOfRequest = $timeOfRequest;'];

        foreach ($command->fields() as $field) {
            $arguments[] = sprintf('        %s $%s', $field['type'], $field['name']);
            $assignments[] = sprintf('        $this->%s = $%s;', $field['name'], $field['name']);
        }

        $arguments = join(",\n", $arguments);
        $assignments = join("\n", $assignments);



        return <<<EOF
    public function __construct(
$arguments
    ) {
$assignments
    }

EOF;

    }

    private function dumpMethods(DefinitionWithFields $command): string
    {
        $methods = [];
        $methods[] = <<<EOF
    public function aggregateRootId(): AggregateRootId
    {
        return \$this->aggregateRootId;
    }


EOF;


        foreach ($command->fields() as $field) {
            $methods[] = <<<EOF
    public function {$field['name']}(): {$field['type']}
    {
        return \$this->{$field['name']};
    }


EOF;
        }

        return join('', $methods);
    }

    private function dumpSerializationMethods(EventDefinition $event)
    {
        $name = $event->name();
        $arguments = [];
        $serializers = [];

        foreach ($event->fields() as $field) {
            $parameter = sprintf('$payload[\'%s\']', $field['name']);
            $template = $event->deserializerForField($field['name'])
                ?: $event->deserializerForType($field['type']);
            $arguments[] = trim(strtr($template, ['{type}' => $field['type'], '{param}' => $parameter]));

            $property = sprintf('$this->%s', $field['name']);
            $template = $event->serializerForField($field['name'])
                ?: $event->serializerForType($field['type']);
            $template = sprintf("'%s' => %s", $field['name'], $template);
            $serializers[] = trim(strtr($template, ['{type}' => $field['type'], '{param}' => $property]));
        }

        $arguments = preg_replace('/^.{2,}$/m', '            $0', join(",\n", $arguments));

        if ( ! empty($arguments)) {
            $arguments = ",\n$arguments";
        }

        $serializers = preg_replace('/^.{2,}$/m', '            $0',join(",\n", $serializers));

        if ( ! empty($serializers)) {
            $serializers = "\n$serializers\n        ";
        }

        return <<<EOF
    public static function fromPayload(
        array \$payload,
        AggregateRootId \$aggregateRootId,
        PointInTime \$timeOfRecording): Event
    {
        return new $name(
            \$aggregateRootId,
            \$timeOfRecording$arguments
        );
    }

    public function toPayload(): array
    {
        return [$serializers];
    }
EOF;


    }

    private function dumpTestHelpers(EventDefinition $event): string
    {
        $constructor = [];
        $constructorArguments = 'AggregateRootId $aggregateRootId, PointInTime $timeOfRecording';
        $constructorValues = ['$aggregateRootId', '$timeOfRecording'];
        $helpers = [];

        foreach ($event->fields() as $field) {
            if ($field['example'] === null) {
                $constructor[] = ucfirst($field['name']);
                $constructorArguments .= sprintf(', %s $%s', $field['type'], $field['name']);
                $constructorValues[] = sprintf('$%s', $field['name']);
            } else {
                $constructorValues[] = $this->dumpConstructorValue($field, $event);
                $method = sprintf('with%s', ucfirst($field['name']));
                $helpers[] = <<<EOF
    public function $method({$field['type']} \${$field['name']}): {$event->name()}
    {
        \$this->{$field['name']} = \${$field['name']};
        
        return \$this;
    }


EOF;
            }
        }

        $constructor = sprintf('with%s', join('And', $constructor));
        $constructorValues = join(",\n            ", $constructorValues);
        $helpers[] = <<<EOF
    public static function $constructor($constructorArguments): {$event->name()}
    {
        return new {$event->name()}(
            $constructorValues
        );
    }


EOF;


        return join('', $helpers);
    }

    private function dumpConstructorValue(array $field, EventDefinition $event): string
    {
        $parameter = rtrim($field['example']);

        if (gettype($parameter) === $field['type']) {
            $parameter = var_export($parameter, true);
        }

        $template = $event->deserializerForField($field['name'])
            ?: $event->deserializerForType($field['type']);

        return rtrim(strtr($template, ['{type}' => $field['type'], '{param}' => $parameter]));
    }

    /**
     * @param CommandDefinition[] $commands
     * @return string
     */
    private function dumpCommands(array $commands): string
    {
        $code = [];

        foreach ($commands as $command) {
            $code[] = <<<EOF
final class {$command->name()} implements Command
{
    /**
     * @var PointInTime
     */
    private \$timeOfRequest;

{$this->dumpFields($command)}{$this->dumpCommandConstructor($command)}
    public function timeOfRequest(): PointInTime
    {
        return \$this->timeOfRequest;
    }

{$this->dumpMethods($command)}}


EOF;
        }

        return join('', $code);
    }
}