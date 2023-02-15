---
permalink: /docs/serialization/plain-serialization/
title: Serialization using ObjectMapper
---

The plain serialization strategy uses an interface to ensure events comply with the needs to serialize and
deserialize events using handwritten property mapping. This strategy is the default, therefore no setup is required.

## Creating events

Events created for this strategy need to implement the `SerializablePayload` interface.

Example:

```php
<?php

namespace AcmeCorp\SomeNamespace;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class SomethingHappened implements SerializablePayload
{
    public function __construct(
        private string $where,
        private DateTimeImmutable $when,
        private int $howManyTimes
    ) {}
    
    // ... getters
    
    public function toPayload(): array
    {
        return [
            'where' => $this->where,
            'when' => $this->when->format('Y-m-d H:i:s'),
            'how_many_times' => $this->howManyTimes,
        ];
    }
    
    public static function fromPayload(array $payload): static
    {
        return new static(
            $payload['where'],
            DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $payload['when']),
            $payload['how_many_times'],
        );
    }
}
```
