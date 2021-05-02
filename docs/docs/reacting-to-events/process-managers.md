---
permalink: /docs/reacting-to-events/process-managers/
title: Process Managers
published_at: 2018-03-07
updated_at: 2020-03-28
---

In EventSauce process managers are nothing more than an implementation of
the `MessageConsumer` interface. Unlike projections, which feed read models, process
managers do more than just respond to something that happened. Process
managers **act**. How the process manager interacts with your system depends
on how you've chosen to model this interaction. You can dispatch commands or
use a service layer to trigger new actions.

## Why are process managers useful?

When modeling large processes, process managers allow you break them up into
multiple steps. This is especially useful when subsequent actions don't require
user interaction. All of these actions can be done in the background, thus not
blocking the user from receiving a response from the server.


## Process Manager Example

In this example I'm using [tactician](https://tactician.thephpleague.com) to
dispatch new commands:

```php
<?php

use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\Message;
Use League\Tactician\CommandBus;

class ProductRestocker implements MessageConsumer
{
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(Message $message)
    {
        $event = $message->event();
        
        if ($event instanceof ProductSoldOut) {
            $this->commandBus->handle(
                new RestockProduct($event->productId())
            );
        }
    }
}
```
