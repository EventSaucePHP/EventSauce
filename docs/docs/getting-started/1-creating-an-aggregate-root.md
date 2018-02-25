---
layout: default
permalink: /docs/getting-started/create-an-aggregate-root/
title: Creating an Aggregate Root
---

# Create an Aggregate Root

An aggregate root is a class that implements the `EventSauce\EventSourcing\AggregateRoot` 
interface. In order to make things easy an abstract class is provided
which implements the interface so you won't have to.

```php
<?php

namespace AcmeCompany\AcmeProject;

use EventSauce\EventSourcing\BaseAggregateRoot;

class AcmeProcess extends BaseAggregateRoot
{
    
}
```

## Aggregate Root ID

An aggregate root has an identifier. This is called the "aggregate root ID".
It's good practise to have a unique type of ID for every aggregate root. This
way you'll be sure not to mix them up you have more than one, because that would
result in a type error (yay for types).

An aggregate root ID needs to implement the `EventSauce\EventSourcing\AggrgeateRootId`
interface.

```php
<?php

namespace AcmeCompany\AcmeProject;

use EventSauce\EventSourcing\AggregateRootId;

class AcmeProcessId implements AggregateRootId
{
    private $id;
    
    private function __construct(string $id)
    {
        $this->id = $id;
    }
    
    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): AggregateRootId
    {
        return new static($id);
    }
}
```

Because the ID implements an interface you can use whatever kind of ID
best fits your use-case. You can use UUID's, or an identifier that's
natural to the domain you're modeling (e.g. a serial number or a unique
group identifier).