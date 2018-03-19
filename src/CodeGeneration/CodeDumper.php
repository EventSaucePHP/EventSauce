<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

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

    public function dump(DefinitionGroup $definitionGroup, bool $withHelpers = true, bool $withSerialization = true): string
    {
        $this->definitionGroup = $definitionGroup;
        $eventsCode = $this->dumpEvents($definitionGroup->events(), $withHelpers, $withSerialization);
        $commandCode = $this->dumpCommands($definitionGroup->commands());
        $namespace = $definitionGroup->namespace();
        $allCode = implode(array_filter([$eventsCode, $commandCode]), "\n\n");

        if ($withSerialization) {
            $namespace .= ";

use EventSauce\EventSourcing\Serialization\SerializableEvent";
        }

        return <<<EOF
<?php

namespace $namespace;

$allCode

EOF;
    }

    private function dumpEvents(array $events, bool $withHelpers, bool $withSerialization): string
    {
        $code = [];

        if (empty($events)) {
            return '';
        }

        foreach ($events as $event) {
            $name = $event->name();
            $fields = $this->dumpFields($event);
            $constructor = $this->dumpConstructor($event);
            $methods = $this->dumpMethods($event);
            $deserializer = $this->dumpSerializationMethods($event);
            $testHelpers = $withHelpers ? $this->dumpTestHelpers($event) : '';
            $implements = $withSerialization ? ' implements SerializableEvent' : '';

            $code[] = <<<EOF
final class $name$implements
{
$fields$constructor$methods$deserializer

$testHelpers}


EOF;
        }

        return rtrim(implode('', $code));
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

        return implode('', $code);
    }

    private function dumpConstructor(DefinitionWithFields $definition): string
    {
        $arguments = [];
        $assignments = [];
        $fields = $this->fieldsFromDefinition($definition);

        if (empty($fields)) {
            return '';
        }

        foreach ($fields as $field) {
            $arguments[] = sprintf('        %s $%s', $field['type'], $field['name']);
            $assignments[] = sprintf('        $this->%s = $%s;', $field['name'], $field['name']);
        }

        $arguments = implode(",\n", $arguments);
        $assignments = implode("\n", $assignments);

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

        foreach ($this->fieldsFromDefinition($command) as $field) {
            $methods[] = <<<EOF
    public function {$field['name']}(): {$field['type']}
    {
        return \$this->{$field['name']};
    }


EOF;
        }

        return empty($methods) ? '' : rtrim(implode('', $methods)) . "\n";
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

        $arguments = preg_replace('/^.{2,}$/m', '            $0', implode(",\n", $arguments));

        if ( ! empty($arguments)) {
            $arguments = "\n$arguments";
        }

        $serializers = preg_replace('/^.{2,}$/m', '            $0', implode(",\n", $serializers));

        if ( ! empty($serializers)) {
            $serializers = "\n$serializers,\n        ";
        }

        return <<<EOF
    public static function fromPayload(array \$payload): SerializableEvent
    {
        return new $name($arguments);
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
        $constructorArguments = '';
        $constructorValues = [];
        $helpers = [];

        foreach ($this->fieldsFromDefinition($event) as $field) {
            if (null === $field['example']) {
                $constructor[] = ucfirst($field['name']);

                if ('' !== $constructorArguments) {
                    $constructorArguments .= ', ';
                }

                $constructorArguments .= sprintf('%s $%s', $field['type'], $field['name']);
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

        $constructor = sprintf('with%s', implode('And', $constructor));
        $constructorValues = implode(",\n            ", $constructorValues);

        if ('' !== $constructorValues) {
            $constructorValues = "\n            $constructorValues\n        ";
        }

        $helpers[] = <<<EOF
    /**
     * @codeCoverageIgnore
     */
    public static function $constructor($constructorArguments): {$event->name()}
    {
        return new {$event->name()}($constructorValues);
    }


EOF;

        return rtrim(implode('', $helpers)) . "\n";
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
     *
     * @return string
     */
    private function dumpCommands(array $commands): string
    {
        $code = [];

        foreach ($commands as $command) {
            $code[] = <<<EOF
final class {$command->name()}
{
{$this->dumpFields($command)}{$this->dumpConstructor($command)}{$this->dumpMethods($command)}}


EOF;
        }

        return rtrim(implode('', $code));
    }

    /**
     * @param DefinitionWithFields $definition
     *
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
