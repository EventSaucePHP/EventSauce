---
permalink: /docs/event-sourcing/create-an-aggregate-root/
redirect_from: /docs/getting-started/create-an-aggregate-root/
title: Creating an Aggregate Root
published_at: 2019-12-07
updated_at: 2020-02-11
---

An event-sourced aggregate root is an aggregate root that is modeled using events.
The default aggregate root repository (`ConstructingAggregateRootRepository`) relies
on the `AggregateRoot` interface, which your aggregate root must implement.

EventSauce provides traits that implement the behavior for an event-sourced
aggregate root.

```php
<?php

namespace AcmeCompany\AcmeProject;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class AcmeProcess implements AggregateRoot
{
    use AggregateRootBehaviour;
}
```

> NOTE: The default reconstitution mechanism allows aggregates to be
> constructed without history. Use the `AggregateRootBehaviourWithRequiredHistory` trait instead.


### Aggregate Construction

The `AggregateRootBehavior` trait includes a constructor, accepting an Aggregate
Root ID. The visibility for this constructor is set to private. It's encouraged
to use named constructors for instance creation. Named constructors are static
methods that combine instance creation and first action(s). This is advised because
this combination conveys very clearly what the entry-points are for an aggregate.

Named constructors have the added benefit of allowing you to tighten the type of
aggregate root identifier. A named constructor would look something like this:

```php
<?php

namespace AcmeCompany\AcmeProject;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class AcmeProcess implements AggregateRoot
{
    use AggregateRootBehaviour;

    public static function initiate(AcmeProcessId $id): AcmeProcess
    {
        $process = new static($id);
        $process->recordThat(new ProcessWasInitiated($id));

        return $process;
    }

    public static function initiateViaImport(AcmeProcessId $id): AcmeProcess
    {
        $process = new static($id);
        $process->recordThat(new ProcessWasInitiated($id));
        $process->recordThat(new ProcessWasStartedBy(ProcessSource::SYSTEM_IMPORT));

        return $process;

    }
}
```

## Applying events

When using the default aggregate root repository implementation (`ConstructingAggregateRootRepository`) together with `AggregateRootBehavior` trait,
it will call the `apply{EventClassName}` on your aggregate root, when applying events.

For example: when you have an event `ProcessStarted`, just implement a `applyProcessStarted` method and it will be called automatically by the repository.

```php
<?php

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ProcessStarted implements SerializablePayload
{
    // ...
}
```

```php
<?php

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class AcmeProcess implements AggregateRoot
{
    use AggregateRootBehaviour;

    // ...

    public function applyProcessStarted(ProcessStarted $processStarted)
    {
        // apply event data to your aggretate
    }
}
```

## Aggregate Root ID

An aggregate root has an identifier. This is called the "aggregate root ID".
It's good practice to have a unique ID class for every aggregate root. This
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
