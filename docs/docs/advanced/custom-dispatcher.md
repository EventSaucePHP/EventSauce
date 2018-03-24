---
permalink: /docs/advanced/custom-dispatcher/
title: Custom Message Dispatcher
published_at: 2018-03-07
updated_at: 2018-03-23
---

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
