<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use InvalidArgumentException;
use LogicException;
use function is_array;
use Symfony\Component\Yaml\Yaml;
use function strpos;
use function var_dump;
use const PATHINFO_EXTENSION;
use function file_get_contents;
use function in_array;
use function is_string;
use function pathinfo;

class YamlDefinitionLoader implements DefinitionLoader
{
    public function canLoad(string $filename): bool
    {
        return in_array(pathinfo($filename, PATHINFO_EXTENSION), ['yaml', 'yml']);
    }

    public function load(string $filename, DefinitionGroup $definitionGroup = null): DefinitionGroup
    {
        /** @var string|bool $fileContents */
        $fileContents = file_get_contents($filename);

        if ( ! is_string($fileContents) || empty($fileContents)) {
            throw new InvalidArgumentException("File {$filename} does not contain anything");
        }

        $definition = Yaml::parse($fileContents);

        if ( ! is_array($definition)) {
            throw new InvalidArgumentException('The definition is incorrectly formatted');
        }

        $definitionGroup = $definitionGroup ?: new DefinitionGroup();

        if (isset($definition['namespace'])) {
            $definitionGroup->withNamespace($definition['namespace']);
        }

        $this->loadInterfaces($definitionGroup, $definition['interfaces'] ?? []);
        $this->loadTypeHandlers($definitionGroup, $definition['types'] ?? []);
        $this->loadFieldDefaults($definitionGroup, $definition['fields'] ?? []);
        $this->loadCommands($definitionGroup, $definition['commands'] ?? []);
        $this->loadEvents($definitionGroup, $definition['events'] ?? []);

        return $definitionGroup;
    }

    private function loadTypeHandlers(DefinitionGroup $definitionGroup, array $types): void
    {
        foreach ($types as $type => $handlers) {
            if (isset($handlers['type'])) {
                $definitionGroup->aliasType($type, $handlers['type']);
            }

            if (isset($handlers['serializer'])) {
                $definitionGroup->typeSerializer($type, $handlers['serializer']);
            }

            if (isset($handlers['deserializer'])) {
                $definitionGroup->typeDeserializer($type, $handlers['deserializer']);
            }
        }
    }

    private function loadCommands(DefinitionGroup $definitionGroup, array $commands): void
    {
        foreach ($commands as $commandName => $commandDefinition) {
            $command = $definitionGroup->command($commandName);
            $this->hydrateDefinition($definitionGroup, $command, $commandDefinition);
        }
    }

    private function loadEvents(DefinitionGroup $definitionGroup, array $events): void
    {
        foreach ($events as $eventName => $eventDefinition) {
            $event = $definitionGroup->event($eventName);
            $this->hydrateDefinition($definitionGroup, $event, $eventDefinition);
        }
    }

    private function loadFieldDefaults(DefinitionGroup $definitionGroup, array $defaults): void
    {
        foreach ($defaults as $field => $default) {
            $definitionGroup->fieldDefault($field, $default['type'], $default['example'] ?? null);

            if (isset($default['serializer'])) {
                $definitionGroup->fieldSerializer($field, $default['serializer']);
            }

            if (isset($default['deserializer'])) {
                $definitionGroup->fieldDeserializer($field, $default['deserializer']);
            }
        }
    }

    private function loadInterfaces(DefinitionGroup $definitionGroup, array $interfaces)
    {
        foreach ($interfaces as $alias => $interfaceName) {
            if ( ! interface_exists($interfaceName)) {
                throw new LogicException("Interface {$interfaceName} does not exist.");
            }

            $definitionGroup->defineInterface($alias, '\\'. ltrim($interfaceName, '\\'));
        }
    }

    /**
     * @param DefinitionGroup   $definitionGroup
     * @param PayloadDefinition $definition
     * @param array             $input
     */
    private function hydrateDefinition(DefinitionGroup $definitionGroup, PayloadDefinition $definition, array $input): void
    {
        $definition->withFieldsFrom($input['fields_from'] ?? '');
        $fields = $input['fields'] ?? [];
        $interfaces = $input['implements'] ?? [];

        foreach ((array) $interfaces as $interface) {
            $definition->withInterface($definitionGroup->resolveInterface($interface));
        }

        foreach ($fields as $fieldName => $fieldDefinition) {
            if (is_string($fieldDefinition)) {
                $fieldDefinition = ['type' => TypeNormalizer::normalize($fieldDefinition)];
            }

            $type = $fieldDefinition['type'] ?? $definitionGroup->typeForField($fieldName);
            $definition->field($fieldName, TypeNormalizer::normalize($type), (string) ($fieldDefinition['example'] ?? null));

            if (isset($fieldDefinition['serializer'])) {
                $definition->fieldSerializer($fieldName, $fieldDefinition['serializer']);
            }

            if (isset($fieldDefinition['deserializer'])) {
                $definition->fieldDeserializer($fieldName, $fieldDefinition['deserializer']);
            }
        }
    }
}
