---
permalink: /docs/upgrading/to-1-0-0
title: Upgrade to 1.0.0
published_at: 2020-04-05
updated_at: 2020-04-05
---

## PHP 8.0

From EventSauce 1.0 a higher PHP version is requires, namely 1.0 and above. 

## Upgrade automation

For those of you who want to speed up the migration from 0.8 to 1.0 there is
a set of [Rector](https://github.com/rectorphp/rector) instructions that
automate a big part of the work.

Check out the [Rector Set](https://github.com/EventSaucePHP/RectorFrom0to1).

## Renames

The `Consumer` class was renamed:

```diff
- use EventSauce\EventSourcing\Consumer;
+ use EventSauce\EventSourcing\MessageConsumer;
```

The Aggregate `AggregateRootTestCase` was put into its own package:

```bash
composer require --dev eventsauce/test-utilities
```

```diff
- use EventSauce\EventSourcing\AggregateRootTestCase;
+ use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;
```

## Clock is moved to a dedicated package

The clock package is included in EventSacue by default. The
classes were moved into their own namespace `EventSauce\Clock`. This
means the `Clock`, `TestClock`, and `SystemClock` have moved.

The `currentTime` method was renamed to `now` to prepare for the
upcoming (PHP-FIG standard for Clocks)[https://github.com/php-fig/fig-standards/blob/master/proposed/clock.md].

If you wish to separately use the clock package, include it using composer:

```bash
composer require eventsauce/clock
```

## Added return types

Some classes were missing return types for `void` returns.

- From `MessageConsumer` (previously `Consumer`), the handle method now has a `void` return type.
- From `MessageRepository`, the handle method now has a `persist` return type.
- From `MessageRepository`, the handle method now has a `persistEvents` return type.

## Extracted packages

The test utilities are shipped separately, install them using:

```bash
composer require --dev eventsauce/test-utilities
```

Code generation is shipped as a separate module, add them to your project using:

```bash
composer require --dev eventsauce/code-generation
```
