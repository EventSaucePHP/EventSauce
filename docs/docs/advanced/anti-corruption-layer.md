---
permalink: /docs/advanced/anti-corruption-layer/
title: Anti-Corruption Layer
---

The integration of systems or processes using messaging or events can lead to unforeseen
information-level coupling. To combat this, message-driven systems use ACLs (anti-corruption layers)
to filter and transform communication between sender and receiver.

EventSauce ships with a set of tools to create rich and versatile ACLs that decrease
information-level coupling. A combination of filters and translators limit and transform
messages, converting producer-centric messages into consumer-centric ones.

Let's see how it all comes together.

## 1. Message Translation

Converting messages is done by message translators. Message translators are classes
that implement the `MessageTranslator` interface.

```php
namespace AcmeCorp\SomeDomain;

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

class MyMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message) : Message
    {
        // convert the message and return it, or return the original message.
    }
}
```

If you do not wish to convert the message at all, you can use the _passthrough_ translator. This 
built-in implementation simply passes on the original message.

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\PassthroughMessageTranslator;

$translator = new PassthroughMessageTranslator();
```

## 2. Message Filtering

Message filters limit the messages an ACL allows to go through. Message filters are classes
that implement the `MessageFilter` interface.

```php
namespace AcmeCorp\SomeDomain;

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilter;use EventSauce\EventSourcing\Message;

class AllowOnlyPublicEvents implements MessageFilter
{
    public function allows(Message $message) : bool
    {
        return $message->event() instanceof PublicEvent;
    }
}

interface PublicEvent {}
```

## 3.1 Create an Outbound ACL

The outbound ACL is a message dispatcher decorator that uses filters and a translator, forwarding
only relevant messages onto the inner dispatcher.

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\AllowAllMessages;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageDispatcher;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher;

/** @var MessageDispatcher $innerDispatcher **/
$innerDispatcher = create_transporting_message_dispatcher();

$messageDispatcher = new AntiCorruptionMessageDispatcher(
    $innerDispatcher,
    new MyMessageTranslator(),
    filterBefore: new AllowOnlyPublicEvents(), // optional
    filterAfter: new AllowAllMessages(), // optional
);

$messageDispatcher->dispatch(new Message(new SomethingHappened('important')));
```

## 3.2 Create an Inbound ACL

The inbound ACL is a message consumer decorator that uses filters and a translator, forwarding
only relevant messages onto the inner consumer.

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\AllowAllMessages;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageConsumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

/** @var MessageConsumer $innerConsumer **/
$innerConsumer = create_transporting_message_consumer();

$messageConsumer = new AntiCorruptionMessageConsumer(
    $innerConsumer,
    new MyMessageTranslator(),
    filterBefore: new AllowOnlyPublicEvents(), // optional
    filterAfter: new AllowAllMessages(), // optional
);

$messageConsumer->handle(new Message(new SomethingHappened('important')));
```

## Built-in message filter implementations

A number of message filters are built-in:

### AllowMessagesWithPayloadOfType

Allows filtering events/payloads by class-names.

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\AllowMessagesWithPayloadOfType;

$filter = new AllowMessagesWithPayloadOfType(
    PublicEvent::class,
    AnotherClassName::class
);
```

### AllowAllMessages

Allows all messages to pass through

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\AllowAllMessages;

$filter = new AllowAllMessages();
```

### NeverAllowMessages

Allows no messages to pass through

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\NeverAllowMessages;

$filter = new NeverAllowMessages();
```

### MatchAllMessageFilters

Only passes on messages if all inner filters allow the message to pass through.

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\MatchAllMessageFilters;

$filter = new MatchAllMessageFilters(
    new MyFilter(),
    new AnotherFilter(),
);
```

### MatchAnyMessageFilter

Passes on messages if _any_ of the inner filters allows the message to pass through.

```php
use EventSauce\EventSourcing\AntiCorruptionLayer\MatchAnyMessageFilter;

$filter = new MatchAnyMessageFilter(
    new MyFilter(),
    new AnotherFilter(),
);
```

## Built-in message translators

A number of messages translators are built-in, enabling rich compositions:

### PassthroughMessageTranslator

Passes on messages unmodified.

```php

use EventSauce\EventSourcing\AntiCorruptionLayer\PassthroughMessageTranslator;

$translator = new PassthroughMessageTranslator();
```

### MessageTranslatorPerPayloadType

Uses a specific translator per payload class-name.

```php

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslatorPerPayloadType;

$translator = new MessageTranslatorPerPayloadType([
    SomePayload::class => new SomePayloadTranslator(),
    AnotherPayload::class => new AnotherPayloadTranslator(),
]);
```

### MessageTranslatorChain

Uses multiple translators and passes the message through each

```php

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslatorChain;

$translator = new MessageTranslatorChain(
    new SomePayloadTranslator(), // first pass
    new AnotherPayloadTranslator(), // second pass
);
```

## Testing Anti corruption layers

> New in 3.1

Anti corruption layers can be tested using the `AntiCorruptionLayerTestCase` class.

```php
class AntiCorruptionLayerTest extends AntiCorruptionLayerTestCase
{
    /**
     * @test
     */
    public function it_passes_trough_all_messages()
    {
        $this->givenMessages(
            new Message(new EventA())
        )->dispatchedThrough(
            fn($dispatcher) => new AntiCorruptionMessageDispatcher(
                $dispatcher,
                new PassthroughMessageTranslator(),
                filterBefore: new AllowAllMessages(),
                filterAfter: new AllowAllMessages(),
            )
        )->expectMessages(
            new Message(new EventA())
        );
    }
    
    /**
     * @test
     */
    public function it_tests_anti_corruption_layer_message_consumer()
    {
        $this->givenEvents(
            new EventA()
        )->consumedThrough(
            fn($consumer) => new AntiCorruptionMessageConsumer(
                $consumer,
                new PassthroughMessageTranslator(),
                filterBefore: new AllowAllMessages(),
                filterAfter: new AllowAllMessages(),
            )
        )->expectEvents(
            new EventA()
        );
    }
}
```

For more elaborate ACL setups, you can configure the ACL once, and run multiple scenarios. By overriding
the `antiCorruptionDispatcher` and `antiCorruptionConsumer` methods, you can specify the default
ACLs used by all test cases in the class.

```php
class AntiCorruptionLayerTest extends AntiCorruptionLayerTestCase
{
    /**
     * Configure the default ACL dispatcher
     */
    protected function antiCorruptionDispatcher(
        MessageDispatcher $dispatcher
    ): AntiCorruptionMessageDispatcher {
        return new AntiCorruptionMessageDispatcher(
            $dispatcher,
            new TranslateEventAToEventB()
        );
    }

    /**
     * Configure the default ACL consumer
     */
    protected function antiCorruptionConsumer(
        MessageConsumer $consumer
    ): AntiCorruptionMessageConsumer {
        return new AntiCorruptionMessageConsumer(
            $consumer,
            new TranslateEventBToEventA()
        );
    }

    /**
     * @test
     */
    public function events_are_converted_back_and_forth()
    {
        $this->givenMessages(
            new Message(new EventA('input value'))
        )->expectMessages(
            new Message(new EventA('input value'))
        );
    }
}
```



