---
permalink: /docs/code-generation/
title: Code Generation
published_at: 2019-11-09
updated_at: 2021-08-24
---

EventSauce provides an simple and opinionated way to generate code. Code generation
is not always to everybody's liking. However, when correct in the right circumstances,
code generation is a great accelerator during your time spent writing code.

## Simple data transfer objects.

Commands and Events are vehicles for transferring information. A common name for these kind
of objects is DTO (Data Transfer Object). Their sole purpose is to carry data and context the
recipient of the object needs in order to interpret it. Commands and events are applied version
of DTO that fulfill a specific purpose. Because DTO only carry information, they are a perfect
match for code generation.

## How does code-gen work in EventSauce?

The library provides a representation of a group of related DTO's called a `DefinitionGroup`. This
group contains *event* and *command* definitions. The library ships with a `CodeDumper` that turns
these definition into PHP code.

```php
<?php

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\DefinitionGroup;

$group = (new DefinitionGroup())->withNamespace('Acme\Something');
$group->event('EventName')
    ->field('property', 'string', 'Example Value');

$codeDumper = new CodeDumper();
$code = $codeDumper->dump($group, false);
```

Results in:

```php
<?php

declare(strict_types=1);

namespace Acme\Something;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class EventName implements SerializablePayload
{
    /**
     * @var string
     */
    private $property;

    public function __construct(
        string $property
    ) {
        $this->property = $property;
    }

    public function property(): string
    {
        return $this->property;
    }

    public static function fromPayload(array $payload): self
    {
        return new self(
            (string) $payload['property']
        );
    }

    public function toPayload(): array
    {
        return [
            'property' => (string) $this->property,
        ];
    }
}
```

The code-generation will make sure the serialization is done for you. For non-scalar types
you'll need to configure how they are serialized and deserialized.

## Configuring custom field types.

You can specify custom field types:

```php
<?php

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\DefinitionGroup;
use Ramsey\Uuid\UuidInterface;

$group = (new DefinitionGroup())->withNamespace('Acme\Something');
$group->aliasType('uuid', UuidInterface::class);
$group->typeSerializer(UuidInterface::class, '{param}->toString()');
$group->typeDeserializer('uuid', '\Ramsey\Uuid\Uuid::fromString({param})');
$group->event('SomeCommand')
    ->field('id', 'uuid');

$codeDumper = new CodeDumper();
$code = $codeDumper->dump($group, false);
```

Which results in:

```php
<?php

declare(strict_types=1);

namespace Acme\Something;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class SomeCommand implements SerializablePayload
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;

    public function __construct(
        \Ramsey\Uuid\UuidInterface $id
    ) {
        $this->id = $id;
    }

    public function id(): \Ramsey\Uuid\UuidInterface
    {
        return $this->id;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new SomeCommand(
            \Ramsey\Uuid\Uuid::fromString($payload['id'])
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }
}
```
