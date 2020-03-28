---
permalink: /docs/reacting-to-events/projections-and-read-models/
title: Projections and Read Models
published_at: 2018-03-07
updated_at: 2019-06-12
---

Projections and read models are a big part of event sourcing. They
are a way to communicating state with the outside world. They're also
very project-specific.

In EventSauce projections are nothing more than the implementation of
the `MessageConsumer` interface.

```php
<?php

namespace EventSauce\EventSourcing;

interface MessageConsumer
{
    public function handle(Message $message);
}
```

The `MessageConsumer` accepts a `Message` via the `handle` method. It can
then retrieve the event from the `Message` to read information
about something important that happened in the business.

## Why read models are important/useful

Read models allow you to separate presentational state from the process
you're modeling. In general this has two effects. Your processing side
is leaner because it doesn't have to deal with any presentation data or
associated presentation logic. On the other your read models are free
from any constraints your domain model has and can be very optimized.

Because projections and read models are fed by a stream of events it's
also a lot easier to create multiple read models. These read models can
even be specific to one use-case and don't have to share restrictions.

## Read model example: friendship requests

As an example we're going to create a couple of read models for the
following case: becoming friends on social media. In this case we'll have
the following events defined:

* `FriendshipRequestWasSent`
* `FriendshipRequestWasCancelled`
* `FriendshipRequestWasAccepted`
* `FriendshipRequestWasDenied`

All these events describe something that happened in the process of becoming
friends on social media.

In our UI we might have two views. One for outgoing invitations and one for incoming.
We can map these UI's to two read models:

* `OutgoingInvitations`
* `IncomingInvitations`

These two read models could very well be placed in a single read model, but for the
purpose of our demonstration we'll separate them.

For each of the read models we can add a projection:

```php
<?php

use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\Message;

class PendingInvitationProjection implements MessageConsumer
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
