<?php

namespace EventSauce\EventSourcing\PestTooling;

use Exception;

function given(object ...$events) {
    return test()->given(...$events);
}

function when(...$arguments) {
    return test()->when(...$arguments);
}

function then(object ...$events) {
    return test()->then(...$events);
}

function expectToFail(Exception $expectedException) {
    return test()->expectToFail($expectedException);
}

function nothingShouldHaveHappened() {
    return test()->nothingShouldHaveHappened();
}

function expectEventOfType(string $class) {
    return test()->expectEventOfType($class);
}

function expectEventToMatch(callable $callable) {
    return test()->expectEventToMatch($callable);
}