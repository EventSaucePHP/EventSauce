---
layout: default
permalink: /docs/reacting-to-events/projections-and-read-models/
title: Projections and Read Models
---

# Projections and Read Models

Projections and read models are a big part of event sourcing. They
our way to communicating state with the outside world. They're also
very project specific.

In EventSauce projections are nothing more than the implementation of
the `Consumer` interface.

```php
<?php

namespace EventSauce\EventSourcing;

interface Consumer
{
    public function handle(Message $message);
}
```

The `Consumer` accepts a `Message` via the `handle` method. And can
then retrieve the `Event` to retrieve information about something
that happened with relevance to the business.

## Why read models are important/useful

Read models allow you to separate presentational state from the process
you're modelling. In general this has two effects. You processing side
is leaner because it doesn't have to deal with any presentation data or
associated presentation logic. On the other your read models are free
to from any constraints your domain model has and can be very optimized.

Because projections and read models are fed by a stream of events it's
also a lot easier to create multiple read models. These read models can
be really specific to one use-case and don't have to share restrictions.

## Read model example: friendship requests

As an example we're going to create a couple read models for a the
following case: becoming friends on social media. In this case we'll have
the following events defined:

* `FriendshipRequestWasSent`
* `FriendshipRequestWasCancelled`
* `FriendshipRequestWasAccepted`
* `FriendshipRequestWasDenied`

All these events describe something that happened in the process of becoming
friends on social media.

In our UI we might have 2 views. One for outgoing invitations and one for incoming.
We can map these UI's to 2 read models:

* `OutgoingInvitations`
* `IncomingInvitations`

These two read models could very well be placed in a single read model, but for the
purpose of our demonstration we'll separate them.

For each of the read models we can add a projection:

```php
<?php

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;

class PendingInvitationProjection implements Consumer
{
    public function __construct(PendingInvitations $invitations)
    {
        $this->invitations = $invitations;
    }
    
    public function handle(Message $message)
    {
        $event = $message->event();
        
        if ($event instanceof FriendshipRequestWasSent) {
            $this->invitations->add(new FriendshipRequest(
                $event->requestId(),
                $event->fromUser(),
                $event->toUser(),
                RequestStatus::pending()
            ));
        } elseif ($event instanceof FriendshipRequestWasCancelled) {
            $this->invitations->updateStatus($event->requestId(), RequestStatus::cancelled());
        } elseif ($event instanceof FriendshipRequestWasAccepted) {
            $this->invitations->updateStatus($event->requestId(), RequestStatus::accepted());
        } elseif ($event instanceof FriendshipRequestWasDenied) {
            // Just remove the request to prevent sad feelings.
            $this->invitations->removeRequest($event->requestId());
        }
    }
}
```

