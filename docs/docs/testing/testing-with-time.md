---
permalink: /docs/testing/testing-with-time/
title: Testing with time
published_at: 2018-03-14
updated_at: 2018-03-23
---

In many cases `time` is a crucial factor in dealing with business
decisions. Testing time-based interactions can be a tedious task.
The base test case (shipped in EventSauce) comes with some time-based 
tooling to help you out.

```php
public function test_function()
{
    $clock = $this->clock();
    $currentTime = $this->currentTime();
}
```

The `clock` and `currentTime` methods are available to retrieve the
`EventSauce\EventSourcing\Time\Clock` and `EventSauce\EventSourcing\Time\PointInTime`
instances. The implementation of the `Clock` is the
`EventSauce\EventSourcing\Time\TestClock` which can be fixated.

```php
public function test_with_fixated_time()
{
    $this->clock()->fixate('2009-01-01 00:00:00');
}
```

## Time in Events

When time is crucial to your domain it makes sense to account for this in
the domain. You're free to use the `PointInTime` objects if those fit your
needs, but you're encouraged to create domain-specific object that model
your case more closely.
