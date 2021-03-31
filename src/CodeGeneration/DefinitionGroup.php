<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

use DateTimeImmutable;
use OutOfBoundsException;
use function array_key_exists;

final class DefinitionGroup
{
    private string $namespace;

    /**
     * @var PayloadDefinition[]
     */
    private array $events = [];
    private array $defaults = [];

    /**
     * @var array<string, string>
     */
    private array $typeSerializer = [
        'string' => '({type}) {param}',
        'array' => '({type}) {param}',
        'integer' => '({type}) {param}',
        'int' => '({type}) {param}',
        'bool' => '({type}) {param}',
        'float' => '({type}) {param}',
    ];

    /**
     * @var array<string, string>
     */
    private array $typeDeserializer = [
        'string' => '({type}) {param}',
        'array' => '({type}) {param}',
        'integer' => '({type}) {param}',
        'int' => '({type}) {param}',
        'bool' => '({type}) {param}',
        'float' => '({type}) {param}',
    ];

    /**
     * @var array<string, string>
     */
    private array $fieldSerializer = [];

    /**
     * @var array<string, string>
     */
    private array $fieldDeserializer = [];

    /**
     * @var PayloadDefinition[]
     */
    private array $commands = [];

    /**
     * @var array<string, string>
     */
    private array $typeAliases = [];

    /**
     * @var array<string, class-string>
     */
    private array $interfaces = [];

    public function __construct()
    {
        $this->typeSerializer(DateTimeImmutable::class, '{param}->format(\'Y-m-d H:i:s.uO\')');
        $this->typeDeserializer(DateTimeImmutable::class, '{type}::createFromFormat(\'Y-m-d H:i:s.uO\', {param})');
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
        /** @var string $type */
        $type = $this->resolveTypeAlias($type);

        $this->typeSerializer[TypeNormalizer::normalize($type)] = $template;
    }

    public function serializerForType(string $type): string
    {
        $type = $this->resolveTypeAlias($type);

        return $this->typeSerializer[$type] ?? 'new {type}({param})';
    }

    public function typeDeserializer(string $type, string $template): void
    {
        $type = $this->resolveTypeAlias($type);

        $this->typeDeserializer[TypeNormalizer::normalize($type)] = $template;
    }

    public function deserializerForType(string $type): string
    {
        $type = $this->resolveTypeAlias($type);

        return $this->typeDeserializer[$type] ?? 'new {type}({param})';
    }

    public function fieldSerializer(string $field, string $template): void
    {
        $this->fieldSerializer[$field] = $template;
    }

    public function serializerForField(string $field): ?string
    {
        return $this->fieldSerializer[$field] ?? null;
    }

    public function fieldDeserializer(string $field, string $template): void
    {
        $this->fieldDeserializer[$field] = $template;
    }

    public function deserializerForField(string $field): ?string
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

    public function resolveTypeAlias(string $alias): string
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

    public function command(string $name): PayloadDefinition
    {
        return $this->commands[] = new PayloadDefinition($this, $name);
    }

    public function typeForField(string $field): string
    {
        return $this->defaults[$field]['type'] ?? 'string';
    }

    public function exampleForField(string $field): mixed
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

    /**
     * @param class-string $interfaceName
     */
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
