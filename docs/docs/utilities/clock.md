---
permalink: /docs/utilities/clock/
title: Clock
published_at: 2019-12-09
updated_at: 2019-12-21
---

In PHP, you can retrieve the current time by creating a `DateTime(Immutable)`
instance. PHP resolves the actual time from a system context. This means that
time is a global resource and global resources are hard to control.

In cases where time (or the passing of time) is important, introducing a
system clock can be very beneficial. A system clock is used to supply
time to you application.

EventSauce ships with two implementations of the `EventSauce\EventSourcing\Time\Clock`
interface. The `EventSauce\EventSourcing\Time\SystemClock` supplies the current time,
and the `EventSauce\EventSourcing\Time\TestClock` can be fixated and manipulated for
testing purposes.

## Using the Clock

```php
<?php

use EventSauce\EventSourcing\PointInTime;
use EventSauce\EventSourcing\Time\SystemClock;

// By default the UTC time zone is used, you can specify the one you need.
$clock = new SystemClock(/* optional */ new DateTimeZone('Europe/Amsterdam'));

/** @var DateTimeImmutable $dateTime */
$dateTime = $clock->dateTime();

/** @var PointInTime $pointInTime */
$pointInTime = $clock->pointInTime();

/** @var DateTimeZone $timeZone */
$timeZone = $clock->timeZone();
```

## Using the TestClock

```php
use EventSauce\EventSourcing\PointInTime;
use EventSauce\EventSourcing\Time\TestClock;

// By default the UTC time zone is used, you can specify the one you need.
$clock = new TestClock(/* optional */ new DateTimeZone('Europe/Amsterdam'));

// Ticking the clock sets the current time to "now".
$clock->tick();

// You can fixate the clock by specifying a point in time (format: Y-m-d H:i:s.u).
$clock->fixate('2020-02-02 02:02:02');

// moving the clock forward using a DateInterval
$clock->moveForward(new DateInterval('PT2H'));
```
