<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use EventSauce\EventSourcing\PointInTime;
use OutOfBoundsException;
use function array_key_exists;

final class DefinitionGroup
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var PayloadDefinition[]
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
        'string' => '({type}) {param}',
        'array' => '({type}) {param}',
        'integer' => '({type}) {param}',
        'int' => '({type}) {param}',
        'bool' => '({type}) {param}',
        'float' => '({type}) {param}',
    ];

    /**
     * @var array
     */
    private $typeDeserializer = [
        'string' => '({type}) {param}',
        'array' => '({type}) {param}',
        'integer' => '({type}) {param}',
        'int' => '({type}) {param}',
        'bool' => '({type}) {param}',
        'float' => '({type}) {param}',
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
     * @var PayloadDefinition[]
     */
    private $commands = [];

    /**
     * @var string[]
     */
    private $typeAliases = [];

    /**
     * @var string[]
     */
    private $interfaces = [];

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

    public function typeSerializer(string $type, string $template): void
    {
        $type = $this->resolveTypeAlias($type);

        $this->typeSerializer[TypeNormalizer::normalize($type)] = $template;
    }

    public function serializerForType($type)
    {
        $type = $this->resolveTypeAlias($type);

        return $this->typeSerializer[$type] ?? 'new {type}({param})';
    }

    public function typeDeserializer(string $type, string $template): void
    {
        $type = $this->resolveTypeAlias($type);

        $this->typeDeserializer[TypeNormalizer::normalize($type)] = $template;
    }

    public function deserializerForType($type)
    {
        $type = $this->resolveTypeAlias($type);

        return $this->typeDeserializer[$type] ?? 'new {type}({param})';
    }

    public function fieldSerializer(string $field, string $template): void
    {
        $this->fieldSerializer[$field] = $template;
    }

    public function serializerForField($field)
    {
        return $this->fieldSerializer[$field] ?? null;
    }

    public function fieldDeserializer(string $field, string $template): void
    {
        $this->fieldDeserializer[$field] = $template;
    }

    public function deserializerForField($field)
    {
        return $this->fieldDeserializer[$field] ?? null;
    }

    public function fieldDefault(string $name, string $type, string $example = null): void
    {
        $type = $this->resolveTypeAlias($type);
        $this->defaults[$name] = compact('type', 'example');
    }

    public function aliasType(string $alias, string $type): void
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

    public function event(string $name): PayloadDefinition
    {
        return $this->events[] = new PayloadDefinition($this, $name);
    }

    public function command(string $name)
    {
        return $this->commands[] = new PayloadDefinition($this, $name);
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
     * @return PayloadDefinition[]
     */
    public function events(): array
    {
        return $this->events;
    }

    /**
     * @return PayloadDefinition[]
     */
    public function commands(): array
    {
        return $this->commands;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function defineInterface(string $alias, string $interfaceName): void
    {
        $this->interfaces[$alias] = $interfaceName;
    }

    public function resolveInterface(string $alias): string
    {
        if ( ! array_key_exists($alias, $this->interfaces)) {
            throw new OutOfBoundsException("Interface not registered for alias ${alias}.");
        }

        return $this->interfaces[$alias];
    }
}
