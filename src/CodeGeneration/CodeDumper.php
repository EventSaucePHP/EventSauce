<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use LogicException;
use function array_filter;
use function array_map;
use function implode;
use function sprintf;
use function ucfirst;
use function var_export;
use const null;

class CodeDumper
{
    /**
     * @var DefinitionGroup
     */
    private $definitionGroup;

    public function dump(DefinitionGroup $definitionGroup, bool $withHelpers = true, bool $withSerialization = true): string
    {
        $this->definitionGroup = $definitionGroup;
        $definitionCode = $this->dumpClasses($definitionGroup->events(), $withHelpers, $withSerialization);
        $commandCode = $this->dumpClasses($definitionGroup->commands(), $withHelpers, $withSerialization);
        $namespace = $definitionGroup->namespace();
        $allCode = implode("\n\n", array_filter([$definitionCode, $commandCode]));

        if ($withSerialization) {
            $namespace .= ";

use EventSauce\EventSourcing\Serialization\SerializablePayload";
        }

        return <<<EOF
<?php

declare(strict_types=1);

namespace $namespace;

$allCode

EOF;
    }

    private function dumpClasses(array $definitions, bool $withHelpers, bool $withSerialization): string
    {
        $code = [];

        if (empty($definitions)) {
            return '';
        }

        foreach ($definitions as $definition) {
            $name = $definition->name();
            $fields = $this->dumpFields($definition);
            $constructor = $this->dumpConstructor($definition);
            $methods = $this->dumpMethods($definition);
            $deserializer = $this->dumpSerializationMethods($definition);
            $testHelpers = $withHelpers ? $this->dumpTestHelpers($definition) : '';
            $implements = $withSerialization ? ' implements SerializablePayload' : '';

            $allSections = [$fields, $constructor, $methods, $deserializer, $testHelpers];
            $allSections = array_filter(array_map('rtrim', $allSections));
            $allCode = implode("\n\n", $allSections);

            $code[] = <<<EOF
final class $name$implements
{
$allCode
}


EOF;
        }

        return rtrim(implode('', $code));
    }

    private function dumpFields(PayloadDefinition $definition): string
    {
        $fields = $this->fieldsFromDefinition($definition);
        $code = [];
        $code[] = <<<EOF

EOF;

        foreach ($fields as $field) {
            $name = $field['name'];
            $type = $this->definitionGroup->resolveTypeAlias($field['type']);

            $code[] = <<<EOF
    /**
     * @var $type
     */
    private \$$name;


EOF;
        }

        return implode('', $code);
    }

    private function dumpConstructor(PayloadDefinition $definition): string
    {
        $arguments = [];
        $assignments = [];
        $fields = $this->fieldsFromDefinition($definition);

        if (empty($fields)) {
            return '';
        }

        foreach ($fields as $field) {
            $resolvedType = $this->definitionGroup->resolveTypeAlias($field['type']);
            $arguments[] = sprintf('        %s $%s', $resolvedType, $field['name']);
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

    private function dumpMethods(PayloadDefinition $command): string
    {
        $methods = [];

        foreach ($this->fieldsFromDefinition($command) as $field) {
            $methods[] = <<<EOF
    public function {$field['name']}(): {$this->definitionGroup->resolveTypeAlias($field['type'])}
    {
        return \$this->{$field['name']};
    }


EOF;
        }

        return empty($methods) ? '' : rtrim(implode('', $methods)) . "\n\n";
    }

    private function dumpSerializationMethods(PayloadDefinition $definition)
    {
        $name = $definition->name();
        $arguments = [];
        $serializers = [];

        foreach ($this->fieldsFromDefinition($definition) as $field) {
            $type = $this->definitionGroup->resolveTypeAlias($field['type']);
            $parameter = sprintf('$payload[\'%s\']', $field['name']);
            $template = $definition->deserializerForField($field['name'])
                ?: $definition->deserializerForType($field['type']);
            $arguments[] = trim(strtr($template, ['{type}' => $type, '{param}' => $parameter]));

            $property = sprintf('$this->%s', $field['name']);
            $template = $definition->serializerForField($field['name'])
                ?: $definition->serializerForType($field['type']);
            $template = sprintf("'%s' => %s", $field['name'], $template);
            $serializers[] = trim(strtr($template, ['{type}' => $type, '{param}' => $property]));
        }

        $arguments = preg_replace('/^.{2,}$/m', '            $0', implode(",\n", $arguments));

        if ( ! empty($arguments)) {
            $arguments = "\n$arguments\n        ";
        }

        $serializers = preg_replace('/^.{2,}$/m', '            $0', implode(",\n", $serializers));

        if ( ! empty($serializers)) {
            $serializers = "\n$serializers,\n        ";
        }

        return <<<EOF
    public static function fromPayload(array \$payload): SerializablePayload
    {
        return new $name($arguments);
    }

    public function toPayload(): array
    {
        return [$serializers];
    }
EOF;
    }

    private function dumpTestHelpers(PayloadDefinition $definition): string
    {
        $constructor = [];
        $constructorArguments = '';
        $constructorValues = [];
        $helpers = [];

        foreach ($this->fieldsFromDefinition($definition) as $field) {
            $resolvedType = $this->definitionGroup->resolveTypeAlias($field['type']);

            if (null === $field['example']) {
                $constructor[] = ucfirst($field['name']);

                if ('' !== $constructorArguments) {
                    $constructorArguments .= ', ';
                }

                $constructorArguments .= sprintf('%s $%s', $resolvedType, $field['name']);
                $constructorValues[] = sprintf('$%s', $field['name']);
            } else {
                $constructorValues[] = $this->dumpConstructorValue($field, $definition);
                $method = sprintf('with%s', ucfirst($field['name']));
                $helpers[] = <<<EOF
    /**
     * @codeCoverageIgnore
     */
    public function $method({$resolvedType} \${$field['name']}): {$definition->name()}
    {
        \$clone = clone \$this;
        \$clone->{$field['name']} = \${$field['name']};

        return \$clone;
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
    public static function $constructor($constructorArguments): {$definition->name()}
    {
        return new {$definition->name()}($constructorValues);
    }


EOF;

        return rtrim(implode('', $helpers)) . "\n";
    }

    private function dumpConstructorValue(array $field, PayloadDefinition $definition): string
    {
        $parameter = rtrim($field['example']);
        $resolvedType = $this->definitionGroup->resolveTypeAlias($field['type']);

        if (gettype($parameter) === $resolvedType) {
            $parameter = var_export($parameter, true);
        }

        $template = $definition->deserializerForField($field['name'])
            ?: $definition->deserializerForType($field['type']);

        return rtrim(strtr($template, ['{type}' => $resolvedType, '{param}' => $parameter]));
    }

    /**
     * @param PayloadDefinition $definition
     *
     * @return array
     */
    private function fieldsFromDefinition(PayloadDefinition $definition): array
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

        foreach ($this->definitionGroup->events() as $definition) {
            if ($definition->name() === $fieldsFrom) {
                return $definition->fields();
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
