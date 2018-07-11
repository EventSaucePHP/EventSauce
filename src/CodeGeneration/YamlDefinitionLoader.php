<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use Symfony\Component\Yaml\Yaml;
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
        $definition = Yaml::parse(file_get_contents($filename));
        $definitionGroup = $definitionGroup ?: new DefinitionGroup();

        if (isset($definition['namespace'])) {
            $definitionGroup->withNamespace($definition['namespace']);
        }

        $this->loadTypeHandlers($definitionGroup, $definition['types'] ?? []);
        $this->loadFieldDefaults($definitionGroup, $definition['fields'] ?? []);
        $this->loadCommands($definitionGroup, $definition['commands'] ?? []);
        $this->loadEvents($definitionGroup, $definition['events'] ?? []);

        return $definitionGroup;
    }

    private function loadTypeHandlers(DefinitionGroup $definitionGroup, array $types)
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

    private function loadCommands(DefinitionGroup $definitionGroup, array $commands)
    {
        foreach ($commands as $commandName => $commandDefinition) {
            $fields = $commandDefinition['fields'] ?? [];
            $command = $definitionGroup->command($commandName);
            $command->withFieldsFrom($commandDefinition['fields_from'] ?? '');

            foreach ($fields as $fieldName => $fieldDefinition) {
                if (is_string($fieldDefinition)) {
                    $fieldDefinition = ['type' => $fieldDefinition];
                }

                $command->field($fieldName, $fieldDefinition['type'], $fieldDefinition['example'] ?? null);

                if (isset($fieldDefinition['serializer'])) {
                    $command->fieldSerializer($fieldName, $fieldDefinition['serializer']);
                }

                if (isset($fieldDefinition['deserializer'])) {
                    $command->fieldDeserializer($fieldName, $fieldDefinition['deserializer']);
                }
            }
        }
    }

    private function loadEvents(DefinitionGroup $definitionGroup, array $events)
    {
        foreach ($events as $eventName => $eventDefinition) {
            $event = $definitionGroup->event($eventName);
            $event->withFieldsFrom($eventDefinition['fields_from'] ?? '');
            $fields = $eventDefinition['fields'] ?? [];

            foreach ($fields as $fieldName => $fieldDefinition) {
                if (is_string($fieldDefinition)) {
                    $fieldDefinition = ['type' => $fieldDefinition];
                }

                $event->field($fieldName, $fieldDefinition['type'], (string) ($fieldDefinition['example'] ?? null));

                if (isset($fieldDefinition['serializer'])) {
                    $event->fieldSerializer($fieldName, $fieldDefinition['serializer']);
                }

                if (isset($fieldDefinition['deserializer'])) {
                    $event->fieldDeserializer($fieldName, $fieldDefinition['deserializer']);
                }
            }
        }
    }

    private function loadFieldDefaults(DefinitionGroup $definitionGroup, array $defaults)
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
}
