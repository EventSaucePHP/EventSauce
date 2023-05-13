<?php

namespace EventSauce\EventSourcing\PestTooling;

use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;
use Exception;

/**
 * @return AggregateRootTestCase
 */
function given(object ...$events) {
    return test()->given(...$events);
}

/**
 * @return AggregateRootTestCase
 */
function when(...$arguments) {
    return test()->when(...$arguments);
}

/**
 * @return AggregateRootTestCase
 */
function then(object ...$events) {
    return test()->then(...$events);
}

/**
 * @return AggregateRootTestCase
 */
function expectToFail(Exception $expectedException) {
    return test()->expectToFail($expectedException);
}

/**
 * @return AggregateRootTestCase
 */
function nothingShouldHaveHappened() {
    return test()->nothingShouldHaveHappened();
}

/**
 * @return AggregateRootTestCase
 */
function expectEventOfType(string $class) {
    return test()->expectEventOfType($class);
}

/**
 * @return AggregateRootTestCase
 */
function expectEventToMatch(callable $callable) {
    return test()->expectEventToMatch($callable);
}