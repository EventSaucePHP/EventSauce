---
permalink: /docs/testing/asserting-the-payload-of-an-event/
title: Asserting the payload of an event
---

Sometimes the exact payload of the expected event is out of scope for the test you are writing. 
In these cases you can do partial assertions against events.

## Asserting an event of a certain class was fired
```php
class DummyIncrementTest extends DummyAggregateRootTestCase
{
    /**
     * @test
     */
    public function it_dispatches_a_event_when_incrementing()
    {
        $this->given(new DummyIncrementingHappened(1))
            ->when(new DummyIncrementCommand($this->aggregateRootId()))
            ->then(
                $this->assertEvent(DummyIncrementingHappened::class)
            )
    } 
}
```

## Asserting part of the payload of an event
It's possible to use a closure in order to run custom assertions against the event that was recorded.
Within the closure PHPUnit's assertions can be used. When false is returned from the closure the tests will fail as well.
```php
class DummyIncrementTest extends DummyAggregateRootTestCase
{
    /**
     * @test
     */
    public function it_dispatches_a_event_when_incrementing()
    {
        $this->given(new DummyIncrementingHappened(1))
            ->when(new DummyIncrementCommand($this->aggregateRootId()))
            ->then(
                $this->assertEvent(DummyIncrementingHappened::class, function (DummyIncrementingHappened $dummyIncrementingHappened): void {
                    $this->assertEquals(2, $dummyIncrementingHappened->number());
                })
            );
    } 
    
        /**
     * @test
     */
    public function it_dispatches_a_event_when_incrementing_using_return_of_closure()
    {
        $this->given(new DummyIncrementingHappened(1))
            ->when(new DummyIncrementCommand($this->aggregateRootId()))
            ->then(
                $this->assertEvent(DummyIncrementingHappened::class, function (DummyIncrementingHappened $dummyIncrementingHappened): bool {
                    return $dummyIncrementingHappened->number() === 2;
                })
            );
    } 
}
```
