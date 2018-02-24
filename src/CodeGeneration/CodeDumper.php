<?php

namespace EventSauce\EventSourcing\CodeGeneration;

use EventSauce\EventSourcing\Event;
use LogicException;
use const null;
use function array_filter;
use function join;
use function sprintf;
use function ucfirst;
use function var_export;

class CodeDumper
{
    /**
     * @var DefinitionGroup
     */
    private $definitionGroup;

    public function dump(DefinitionGroup $definitionGroup, bool $withHelpers = true): string
    {
        $this->definitionGroup = $definitionGroup;
        $eventsCode = $this->dumpEvents($definitionGroup->events(), $withHelpers);
        $commandCode = $this->dumpCommands($definitionGroup->commands());
        $namespace = $definitionGroup->namespace();
        $allCode = join(array_filter([$eventsCode, $commandCode]), "\n\n");

        return <<<EOF
<?php

namespace $namespace;

use EventSauce\\EventSourcing\\AggregateRootId;
use EventSauce\\EventSourcing\\Command;
use EventSauce\\EventSourcing\\Event;
use EventSauce\\EventSourcing\\PointInTime;

$allCode

EOF;
    }

    private function dumpEvents(array $events, bool $withHelpers): string
    {
        $code = [];

        if (empty($events)) {
            return '';
        }

        foreach ($events as $event) {
            $name = $event->name();
            $fields = $this->dumpFields($event);
            $constructor = $this->dumpEventConstructor($event);
            $methods = $this->dumpMethods($event);
            $deserializer = $this->dumpSerializationMethods($event);
            $testHelpers = $withHelpers ? $this->dumpTestHelpers($event) : '';

            $code[] = <<<EOF
final class $name implements Event
{
$fields    /**
     * @var PointInTime
     */
    private \$timeOfRecording;

$constructor$methods    public function timeOfRecording(): PointInTime
    {
        return \$this->timeOfRecording;
    }

$deserializer

$testHelpers}


EOF;
        }

        return rtrim(join('', $code));
    }

    private function dumpFields(DefinitionWithFields $definition): string
    {
        $fields = $this->fieldsFromDefinition($definition);
        $code = [];
        $code[] = <<<EOF

EOF;


        foreach ($fields as $field) {
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
        $arguments = ['        PointInTime $timeOfRecording'];
        $assignments = ['        $this->timeOfRecording = $timeOfRecording;'];
        $fields = $this->fieldsFromDefinition($event);

        foreach ($fields as $field) {
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
        $arguments = ['        PointInTime $timeOfRequest'];
        $assignments = ['        $this->timeOfRequest = $timeOfRequest;'];
        $fields = $this->fieldsFromDefinition($command);

        foreach ($fields as $field) {
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


        foreach ($this->fieldsFromDefinition($command) as $field) {
            $methods[] = <<<EOF
    public function {$field['name']}(): {$field['type']}
    {
        return \$this->{$field['name']};
    }


EOF;
        }

        return rtrim(join('', $methods)) . "\n\n";
    }

    private function dumpSerializationMethods(EventDefinition $event)
    {
        $name = $event->name();
        $arguments = [];
        $serializers = [];

        foreach ($this->fieldsFromDefinition($event) as $field) {
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

        $serializers = preg_replace('/^.{2,}$/m', '            $0', join(",\n", $serializers));

        if ( ! empty($serializers)) {
            $serializers = "\n$serializers,";
        }

        $eventVersionPayloadKey = Event::EVENT_VERSION_PAYLOAD_KEY;

        return <<<EOF
    public static function fromPayload(
        array \$payload,
        PointInTime \$timeOfRecording): Event
    {
        return new $name(
            \$timeOfRecording$arguments
        );
    }

    public function toPayload(): array
    {
        return [$serializers
            '$eventVersionPayloadKey' => {$event->version()},
        ];
    }
EOF;


    }

    private function dumpTestHelpers(EventDefinition $event): string
    {
        $constructor = [];
        $constructorArguments = 'PointInTime $timeOfRecording';
        $constructorValues = ['$timeOfRecording'];
        $helpers = [];

        foreach ($this->fieldsFromDefinition($event) as $field) {
            if ($field['example'] === null) {
                $constructor[] = ucfirst($field['name']);
                $constructorArguments .= sprintf(', %s $%s', $field['type'], $field['name']);
                $constructorValues[] = sprintf('$%s', $field['name']);
            } else {
                $constructorValues[] = $this->dumpConstructorValue($field, $event);
                $method = sprintf('with%s', ucfirst($field['name']));
                $helpers[] = <<<EOF
    /**
     * @codeCoverageIgnore
     */
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

        return rtrim(join('', $code));
    }

    /**
     * @param DefinitionWithFields $definition
     * @return array
     */
    private function fieldsFromDefinition(DefinitionWithFields $definition): array
    {
        $fields = $this->fieldsFrom($definition->fieldsFrom());

        foreach ($definition->fields() as $field) {
            array_push($fields, $field);
        }

        return $fields;
    }

    private function fieldsFrom(string $fieldsFrom): array
    {
        if (empty($fieldsFrom)) {
            return [];
        }

        foreach ($this->definitionGroup->events() as $event) {
            if ($event->name() === $fieldsFrom) {
                return $event->fields();
            }
        }

        foreach ($this->definitionGroup->commands() as $command) {
            if ($command->name() === $fieldsFrom) {
                return $command->fields();
            }
        }

        throw new LogicException("Could not inherit fields from {$fieldsFrom}.");
    }
}