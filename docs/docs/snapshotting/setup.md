---
permalink: /docs/snapshotting/setup/
title: Snapshotting Setup
published_at: 2019-09-25
updated_at: 2020-03-02
---

Setting up snapshotting consists of a couple of steps:

> 1. Prepare your aggregate root.
> 2. Use the snapshot aggregate repository.
> 3. Use it in your application.

## 1. Prepare your aggregate

Enable your aggregate root by implementing the `AggregateRootWithSnapshotting` interface. The
`SnapshottingBehaviour` trait is a great base for implementing it. An example aggregate root
with snapshotting capabilities looks like:

```php
<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\SnapshottingBehaviour;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;

class LightSwitch implements AggregateRootWithSnapshotting
{
    use AggregateRootBehaviour;
    use SnapshottingBehaviour;

    const OFF = false;
    const ON = true;

    private $state = self::OFF;

    private function createSnapshotState()
    {
        return $this->state;
    }

    public function state(): bool
    {
        return $this->state;
    }

    public function turnOn(): void
    {
        if (self::OFF == $this->state) {
            $this->recordThat(LightSwitchWasFlipped::on());
        }
    }

    public function turnOff(): void
    {
        if (self::ON == $this->state) {
            $this->recordThat(LightSwitchWasFlipped::off());
        }
    }

    protected function applyLightSwitchWasFlipped(LightSwitchWasFlipped $event): void
    {
        $this->state = $event->state();
    }

    protected static function reconstituteFromSnapshotState(AggregateRootId $id, bool $state): AggregateRootWithSnapshotting
    {
        $lightSwitch = new static($id);
        $lightSwitch->state = $state;

        return $lightSwitch;
    }
}
```

## 2. Use the snapshot aggregate repository.

The interface for interacting with aggregates and snapshots is defined in the
`AggregateRootRepositoryWithSnapshotting` interface. A concrete implementation
is provided in the form of the `ConstructingAggregateRootRepositoryWithSnapshotting` class.

The implementation needs an implementation of the `SnapshotRepository`. You can
use any storage implementation and storage type that fits your needs. As long as
it satisfies the interface, it's all good.

You can construct the repository like this:

```php
$aggregateRepository = new ConstructingAggregateRootRepositoryWithSnapshotting(
    $aggregateRootClassName,
    $messageRepository,
    $snapshotRepository,
    $regularAggregateRootRepository
);
```

As you can see above, the repository requires the regular repository to be injected
into it. This allows you to transparently replace the existing usage without
breaking anything. In addition you now have added snapshot capabilities to the repository!

## 3. Use it in your application.

Now that you have snapshotting capabilities at your disposal, we can start to use it.

In order to store a snapshot, first retrieve an aggregate root as usual:

```php
$aggregate = $aggregateRepository->retrieve($aggregateRootId);
```

Next, we can store a snapshot:

```php
$aggregateRepository->persist($aggregate); // (optional) store new events first!
$aggregateRepository->storeSnapshot($aggregate);
```

Remember that if you've interacted with the model, first persist the aggregate root before
storing the snapshot. This prevents any split-brain situations where the snapshot is further
ahead than the event-stream.

You can now retrieve an aggregate from a snapshot:

```php
$aggregate = $aggregateRepository->retrieveFromSnapshot($aggregateId);
```

This will make sure you'll retrieve the aggregate in the right way. When there's no previously
stored snapshot, the repository delegates the construction to the original repository, which
creates the aggregate from the event stream.
