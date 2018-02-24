# Creating aggregate roots

Creating aggregate roots is as simple as extending a base class providing the functionality to record and
release events.

```php
<?php

use EventSauce\EventSourcing\BaseAggregateRoot;
use EventSauce\EventSourcing\Time\Clock;

class SomeBusinessProcess extends BaseAggregateRoot
{
    private $reasonsForTasks = [];
    
    public function performTask(string $reason, Clock $clock)
    {
        $this->recordThat(new TaskWasExecuted(
            $this->aggregateRootId(),
            $clock->pointInTime(),
            $reason
        ));
    }
    
    protected function applyTaskWasExecuted(TaskWasExecuted $event)
    {
        $this->reasonsForTasks[] = $event->reason();
    }
}
```

As shown in the example above, the base class provides the `recordThat` method which records events that need to be persisted/dispatched later. The method `performTask` also receives a `Clock`.

The `Clock` object provides the current time, represented by a `PointInTime`, a microsecond precise object. It's so precise that two instances created after each other usually won't have the same value.
 
One of the core concepts in EventSauce is that every event records the `PointInTime` it occured. This is useful for business analysis but is also allows us to find the original order of the events after they've been persisted. The `BaseAggregateRoot` needs a `Clock` to provide this time to each event. 

EventSauce provides a couple `Clock` implementations out of the box. In production, use the  `SystemClock`, which returns the current time of the operating system. For testing, you can use `TestClock` is provides which allows you to provide a fixed time. This is very useful because using the system time in tests makes it impossible to hardcode your fixtures.
