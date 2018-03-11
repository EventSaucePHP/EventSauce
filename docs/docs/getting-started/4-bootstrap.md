---
permalink: /docs/getting-started/bootstrap-and-use/
title: Bootstrap
published_at: 2018-03-11
updated_at: 2018-03-11
---

Now that you've got your MessageRepository and MessageDispatcher in place
you're set to bootstrap your `AggregateRootRepository`.

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
