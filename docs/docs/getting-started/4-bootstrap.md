---
layout: default
permalink: /docs/getting-started/bootstrap-and-use/
title: Configure Persistence
---

# Bootstrap 

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