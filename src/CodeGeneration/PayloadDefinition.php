<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\CodeGeneration;

final class PayloadDefinition
{
    /**
     * @var DefinitionGroup
     */
    private $group;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array[]
     */
    private $fields = [];

    /**
     * @var string
     */
    private $fieldsFrom = '';

    /**
     * @var array
     */
    private $fieldSerializers = [];

    /**
     * @var array
     */
    private $fieldDeserializers = [];

    /**
     * @var string[]
     */
    private $interfaces = [];

    public function __construct(DefinitionGroup $group, string $name)
    {
        $this->name = $name;
        $this->group = $group;
    }

    /**
     * @return $this
     */
    public function withFieldsFrom(string $otherType): PayloadDefinition
    {
        $this->fieldsFrom = $otherType;

        return $this;
    }

    public function withInterface(string $interface): PayloadDefinition
    {
        $this->interfaces[] = $interface;

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function fieldsFrom(): string
    {
        return $this->fieldsFrom;
    }

    public function field(string $name, string $type, string $example = null): PayloadDefinition
    {
        $example = $example ?: $this->group->exampleForField($name);
        $this->fields[] = compact('name', 'type', 'example');

        return $this;
    }

    public function fieldSerializer(string $field, string $template): PayloadDefinition
    {
        $this->fieldSerializers[$field] = $template;

        return $this;
    }

    public function serializerForField(string $field): ?string
    {
        return $this->fieldSerializers[$field] ?? $this->group->serializerForField($field);
    }

    public function fieldDeserializer(string $field, string $template): self
    {
        $this->fieldDeserializers[$field] = $template;

        return $this;
    }

    public function deserializerForField(string $fieldName): ?string
    {
        return $this->fieldDeserializers[$fieldName] ?? $this->group->deserializerForField($fieldName);
    }

    public function deserializerForType(string $type): string
    {
        return $this->group->deserializerForType($type);
    }

    public function serializerForType(string $type): string
    {
        return $this->group->serializerForType($type);
    }

    public function interfaces(): array
    {
        return $this->interfaces;
    }
}
