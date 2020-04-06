---
permalink: /docs/upgrading/to-1-0-0
title: Upgrade to 1.0.0
published_at: 2020-04-05
updated_at: 2020-04-05
---

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

## Extracted packages

The test utilities are shipped separately, install them using:

```bash
composer require --dev eventsauce/test-utilities
```

Code generation is shipped as a separate module, add them to your project using:

```bash
composer require --dev eventsauce/code-generation
```
