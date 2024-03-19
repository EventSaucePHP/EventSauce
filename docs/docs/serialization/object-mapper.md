---
permalink: /docs/serialization/object-mapper/
title: Serialization using ObjectMapper
---

The object hydrator based serialization provides an easy way to serialize events to
plain/scalar data structures. Unlike [plain serialization](/docs/serialization/plain-serialization/)
this serialization implementation doesn't require you to write your own mapping code.
Instead, it uses a combination of conventions and [PHP 8 Attributes](https://www.php.net/manual/en/language.attributes.overview.php)
to map objects to raw data and back.

For backwards-compatibility reasons this is not the default strategy, so you'll need
to configure your setup to use it.

```php
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\ObjectMapperPayloadSerializer;

$serializar = new ConstructingMessageSerializer(
    payloadSerializer: new ObjectMapperPayloadSerializer()
);

// next, inject this serializer where-ever you need it :)
```

## Creating events

Using the Object Mapper serialization, your events need to
comply with the conventions of the underlying package. These are

1. The constructor is used during deserialization.
2. All constructor parameters are converted to snake_case when looking for input
3. All getters and public properties are used for deserialization
4. All getter names and public property names are converted to snake_case for serialization

An example events:

```php
<?php

namespace AcmeCorp\SomeNamespace;

class SomethingHappened
{
    public function __construct(
        private string $where,
        private DateTimeImmutable $when,
        private int $howManyTimes
    ) {}
    
    public function where(): string
    {
        return $this->where;
    }
    
    public function when(): DateTimeImmutable
    {
        return $this->when;
    }
    
    public function howManyTimes(): int
    {
        return $this->howManyTimes;
    }
}
```

### Advanced usage

For advanced usage, checkout the [eventsauce/object-hydrator documentation](https://github.com/EventSaucePHP/ObjectHydrator#usage).
