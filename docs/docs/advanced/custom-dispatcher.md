---
layout: default
permalink: /docs/advanced/custom-dispatcher/
title: Custom Message Dispatcher
---

# Custom Message Dispatcher

You can create a custom implementation of the message dispatcher if needed. Your
class must implement the `MessageDispatcher` interface:

```php
<?php

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    public function dispatch(Message ... $messages);
}
```

It's recommended to leverage the `MessageSerializer` capabilities, just like
the [custom repository](/docs/advanced/custom-repository) does.
 