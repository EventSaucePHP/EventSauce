---
permalink: /docs/advanced/upcasting/
title: Upcasting
published_at: 2018-03-07
updated_at: 2020-04-05
---

Using events to model software can generate interesting
new insights. These insights can cause you to uncover
things that you may have wished to have done differently.

Upcasting allows you to transform the raw event data before
being turned into event classes. They allow you to make
small corrections and fix some mistakes.

In EventSauce _upcasting_ is facilitated in the serialization
process. The `EventSauce\EventSourcing\Upcasting\UpcastingMessageSerializer`
class can be used to add one or more upcasting transformations.

Each upcaster must implement the `EventSauce\EventSourcing\Upcasting\Upcaster`
interface:

```php
<?php

namespace EventSauce\EventSourcing\Upcasting;

interface Upcaster
{
    public function upcast(array $message): array;
}
```

## Using multiple upcasters

You can use multiple upcasters using the `UpcasterChain`.

```php
<?php

use EventSauce\EventSourcing\Upcasting\UpcasterChain;
use EventSauce\EventSourcing\Upcasting\UpcastingMessageSerializer;

$upcastingSerializer = new UpcastingMessageSerializer(
    $actualSerializer,
    new UpcasterChain(new UpcasterOne(), new UpcasterTwo())
);
```
