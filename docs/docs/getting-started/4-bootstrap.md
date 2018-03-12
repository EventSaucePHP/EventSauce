---
permalink: /docs/getting-started/bootstrap/
title: Bootstrap
alternate_title: Bootstrapping EventSauce
published_at: 2018-03-11
updated_at: 2018-03-12
---

Now that you've got your MessageRepository and MessageDispatcher in place
you're ready to bootstrap your `AggregateRootRepository`.

```php
<?php

use EventSauce\EventSourcing\AggregateRootRepository;

$aggregateRootRepository = new AggregateRootRepository(
    YourAggregateRootClass::class,
    $messageRepository,
    $messageDispatcher
);
```

Now you're ready to start modeling! For more information about how you do this, read the [lifecycle](/docs/lifecycle/)
documentation.
