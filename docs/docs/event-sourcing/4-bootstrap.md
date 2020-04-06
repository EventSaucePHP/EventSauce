---
permalink: /docs/event-sourcing/bootstrap/
redirect_from: /docs/getting-started/bootstrap/
title: Bootstrap
alternate_title: Bootstrapping EventSauce
published_at: 2019-12-07
updated_at: 2019-12-21
---

Now that you've got your `MessageRepository` and `MessageDispatcher` in place
you're ready to bootstrap your `AggregateRootRepository`.

The `AggregateRootRepository` is the main interface of EventSauce. It's where
you get your aggregate root from and where you persist (persist recorded events)
it. When you're retrieving an aggregate root from the repository it's responsible
for fetching the associated events from the `MessageRepository` and using that
to create an aggregate root. This process is commonly referred to as
**reconstituting an aggregate root**.

The default implementation shipped in EventSauce is the `ConstructingAggregateRootRepository`.
This repository is a sane default implementation and also serves as a reference
implementation for if/when you want to create your own implementation.

```php
<?php

use EventSauce\EventSourcing\ConstructingAggregateRootRepository;

$aggregateRootRepository = new ConstructingAggregateRootRepository(
    YourAggregateRootClass::class,
    $messageRepository,
    $messageDispatcher
);
```

Now you're ready to start modeling! For more information about how you do this, read the [lifecycle](/docs/lifecycle/)
documentation.
