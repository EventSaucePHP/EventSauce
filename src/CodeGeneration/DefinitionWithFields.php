<?php

namespace EventSauce\EventSourcing\CodeGeneration;

abstract class DefinitionWithFields
{
    /**
     * @var DefinitionGroup
     */
    protected $group;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array[]
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $fieldsFrom = '';

    /**
     * @var array
     */
    protected $fieldSerializers = [];

    /**
     * @var array
     */
    protected $fieldDeserializers = [];

    public function __construct(DefinitionGroup $group, string $name)
    {
        $this->name = $name;
        $this->group = $group;
    }

    /**
     * @param string $otherType
     * @return $this
     */
    public function withFieldsFrom(string $otherType)
    {
        $this->fieldsFrom = $otherType;

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

    public function field(string $name, string $type = null, string $example = null)
    {
        $type = $this->group->resolveTypeAlias($type);
        $type = TypeNormalizer::normalize($type ?: $this->group->typeForField($name));
        $example = $example ?: $this->group->exampleForField($name);
        $this->fields[] = compact('name', 'type', 'example');

        return $this;
    }

    public function fieldSerializer($field, $template)
    {
        $this->fieldSerializers[$field] = $template;

        return $this;
    }

    public function serializerForField($field)
    {
        return $this->fieldSerializers[$field] ?? $this->group->serializerForField($field);
    }

    public function fieldDeserializer($field, $template)
    {
        $this->fieldDeserializers[$field] = $template;

        return $this;
    }

    public function deserializerForField($field)
    {
        return $this->fieldDeserializers[$field] ?? $this->group->deserializerForField($field);
    }

    public function deserializerForType($type)
    {
        return $this->group->deserializerForType($type);
    }

    public function serializerForType($type)
    {
        return $this->group->serializerForType($type);
    }
}