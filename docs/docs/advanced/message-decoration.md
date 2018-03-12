---
permalink: /docs/advanced/message-decoration/
title: Message Decoration
published_at: 2018-03-11
updated_at: 2018-03-12
---

EventSauce provides the possibility to decorate messaged. What this
means is that you can add headers to messages to provide extra
contextual information before a message is persisted and/or dispatched.

By default the `DefaultHeaderDecorator` is used by the `AggregateRootRepository`.
This decorator copies the `time of recording` from payload as headers. It 
ensures the `Header::EVENT_TYPE` is detected and filled. It also pre-processes
the _aggregate root id_ by turning it into a string and adding type information.

## Using custom decorators

You can use custom decorator, but the `DefaultHeaderDecorator` must also be
provided. The `MessageDecoratorChain` can be used to combine your custom
decorators with the default one.

```php
<?php

use EventSauce\EventSourcing\DefaultHeadersDecorator;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDecoratorChain;

class YourDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withHeader('x-decorated-by', 'Frank de Jonge');
    }
}

$decoratorChain = new MessageDecoratorChain(
    new DefaultHeadersDecorator(),
    new YourDecorator()
);
```

Now that you've got your decorator chain setup you can use it as one of the
constructor arguments of the `AggregateRootRepository`.

```php
<?php

use EventSauce\EventSourcing\AggregateRootRepository;

$repository = new AggregateRootRepository(
    YourAggregate::class,
    $messageRepository,
    $optionalDispatcher, // or NULL
    $decoratorChain
);
```

From now on every message that's released by one of your aggregate roots will
have additional headers.

## Adding multiple headers at once.

It's also possible to add multiple headers at once using `::withHeaders`:

```php
<?php

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class YourDecorator implements MessageDecorator
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }
    
    public function decorate(Message $message): Message
    {
        return $message->withHeaders([
            'x-decorated-by' => 'Frank de Jonge',
            'x-request-id' => $context->requestIdentifier(),
        ]);
    }
}
```

> # Message Immutability
>
> It's important to note that `Message` objects are modelled as immutable
> objects. The `withHeader` and `withHeaders` methods return a new (cloned)
> version with the added headers.
