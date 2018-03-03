---
layout: default
permalink: /docs/testing/
title: Testing
---

# Testing Aggregates

Event sourced applications are very easy to test. EventSauce ships with test tooling
that allows for scenario based testing using a given/when/then structure. This kind
testing falls under the category of Behavior Driven Development (BDD).

Test written in this style are very expressive, easy to read. They also make it very
easy to document (and test) business requirements. The test tooling makes it easy
to follow the TDD approach. Creating tests (and the required events) often gives
very usable insights when modeling your application.

## 1. Create a base test case for your aggregate.

It's advised to create a base test for your aggregate.

```php
<?php

namespace AmceCompany\SigningUp;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;

abstract class SignUpProcess extends AggregateRootTestCase
{
    protected function aggregateRootId(): AggregateRootId
    {
        // Implement me.
    }

    protected function aggregateRootClassName(): string
    {
        // Implement me.
    }
    
    protected function handle(/* your arguments for when */)
    {
        // Implement me.
    }
}

```