---
permalink: /docs/getting-started/create-an-aggregate-root/
title: Creating an Aggregate Root
published_at: 2018-03-11
updated_at: 2018-03-12
---

An aggregate root is a class that implements the `EventSauce\EventSourcing\AggregateRoot` 
interface. In order to make things easy several traits are provided
which implements the interface so you won't have to.

```php
<?php

namespace AcmeCompany\AcmeProject;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootBehaviour\ConstructionBehaviour;
use EventSauce\EventSourcing\AggregateRootBehaviour\EventApplyingBehaviour;
use EventSauce\EventSourcing\AggregateRootBehaviour\EventRecordingBehaviour;

class AcmeProcess implements AggregateRoot
{
    // Use all traits.
    use AggregateRootBehaviour;

    // OR use individually
    use ConstructionBehaviour,
        EventApplyingBehaviour,
        EventRecordingBehaviour;
}
```

If you *really* lazy, you can also extend the `EventSauce\EventSourcing\BaseAggregateRoot`
base class.

## Aggregate Root ID

An aggregate root has an identifier. This is called the "aggregate root ID".
It's good practice to have a unique type of ID for every aggregate root. This
way you'll be sure not to mix them up if you're juggling more than one at the same
time, since that would result in a type error (yay for types).

An aggregate root ID needs to implement the `EventSauce\EventSourcing\AggregateRootId`
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
best fits your use-case. You can use UUIDs or an identifier that's
natural to the domain you're modeling (e.g. a serial number or a unique
group identifier).

Having unique ID classes for each type of aggregate has an added benefit
when you're refactoring and events or commands move to a different aggregate. The 
types will assure you're using the right kind of ID. The fact a ProductId
and a UserID might both be UUIDs under the hood is just a coincidence,
not their defining feature.
