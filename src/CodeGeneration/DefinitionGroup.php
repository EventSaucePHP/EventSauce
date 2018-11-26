<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use EventSauce\EventSourcing\PointInTime;

final class DefinitionGroup
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var EventDefinition[]
     */
    private $events = [];

    /**
     * @var array
     */
    private $defaults = [];

    /**
     * @var array
     */
    private $typeSerializer = [
        'string'  => '({type}) {param}',
        'array'   => '({type}) {param}',
        'integer' => '({type}) {param}',
        'int'     => '({type}) {param}',
        'bool'    => '({type}) {param}',
        'float'   => '({type}) {param}',
    ];

    /**
     * @var array
     */
    private $typeDeserializer = [
        'string'  => '({type}) {param}',
        'array'   => '({type}) {param}',
        'integer' => '({type}) {param}',
        'int'     => '({type}) {param}',
        'bool'    => '({type}) {param}',
        'float'   => '({type}) {param}',
    ];

    /**
     * @var array
     */
    private $fieldSerializer = [];

    /**
     * @var array
     */
    private $fieldDeserializer = [];

    /**
     * @var CommandDefinition[]
     */
    private $commands = [];

    /**
     * @var string[]
     */
    private $typeAliases = [];

    public function __construct()
    {
        $this->typeSerializer(PointInTime::class, '{param}->toString()');
        $this->typeDeserializer(PointInTime::class, '{type}::fromString({param})');
    }

    public static function create(string $namespace): DefinitionGroup
    {
        return (new DefinitionGroup())->withNamespace($namespace);
    }

    public function withNamespace(string $namespace): DefinitionGroup
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function typeSerializer(string $type, string $template)
    {
        $type = $this->resolveTypeAlias($type);

        $this->typeSerializer[TypeNormalizer::normalize($type)] = $template;
    }

    public function serializerForType($type)
    {
        $type = $this->resolveTypeAlias($type);

        return $this->typeSerializer[$type] ?? 'new {type}({param})';
    }

    public function typeDeserializer(string $type, string $template)
    {
        $type = $this->resolveTypeAlias($type);

        $this->typeDeserializer[TypeNormalizer::normalize($type)] = $template;
    }

    public function deserializerForType($type)
    {
        $type = $this->resolveTypeAlias($type);

        return $this->typeDeserializer[$type] ?? 'new {type}({param})';
    }

    public function fieldSerializer(string $field, string $template)
    {
        $this->fieldSerializer[$field] = $template;
    }

    public function serializerForField($field)
    {
        return $this->fieldSerializer[$field] ?? null;
    }

    public function fieldDeserializer(string $field, string $template)
    {
        $this->fieldDeserializer[$field] = $template;
    }

    public function deserializerForField($field)
    {
        return $this->fieldDeserializer[$field] ?? null;
    }

    public function fieldDefault(string $name, string $type, string $example = null)
    {
        $type = $this->resolveTypeAlias($type);
        $this->defaults[$name] = compact('type', 'example');
    }

    public function aliasType(string $alias, string $type)
    {
        $this->typeAliases[$alias] = TypeNormalizer::normalize($type);
    }

    public function resolveTypeAlias(string $alias = null)
    {
        while (isset($this->typeAliases[$alias])) {
            $alias = $this->typeAliases[$alias];
        }

        return $alias;
    }

    public function event(string $name): EventDefinition
    {
        return $this->events[] = new EventDefinition($this, $name);
    }

    public function command(string $name)
    {
        return $this->commands[] = new CommandDefinition($this, $name);
    }

    public function typeForField(string $field): string
    {
        return $this->defaults[$field]['type'] ?? 'string';
    }

    public function exampleForField(string $field)
    {
        return $this->defaults[$field]['example'] ?? null;
    }

    /**
     * @return EventDefinition[]
     */
    public function events(): array
    {
        return $this->events;
    }

    /**
     * @return CommandDefinition[]
     */
    public function commands(): array
    {
        return $this->commands;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }
}
