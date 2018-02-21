# Creating aggregate roots

Creating aggregate roots as as simple as extending a base class which provides the functionality required to record and
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

The aggregate root base class defined a couple of methods. As shown in the example above, it provides the `recordThat`
method which records events that need to be persisted/dispatched later. The method `performTask` also receives a `Clock`.
This is one of the libraries opinions. Every event has a time of recording, which is a `PointInTime`. This is a microsecond
precise object. It's so precise that two instances created after each-other don't have the same value.

> For general usage throughout your application a `Clock` implementation is provided in the form of a `SystemClock`, this
> generates a new `PointInTime` object. For testing purposes a `TestClock` is provides which allows you to fixate time.
> This is extremely handy in situations where time is relevant for handling events.

