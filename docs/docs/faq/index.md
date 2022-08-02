---
permalink: /docs/faq/
title: Frequently Asked Questions
---

> How can I retrieve a specific version of an aggregate to e.g. compare two different versions?

You'll notice that the `AggregateRootRepository` does not contain any method to retrieve a given aggregate in a specific
version. Fetching an aggregate in a specific version directly from the `AggregateRootRepository` might be tempting at
first, but it is actually an anti-pattern in event sourcing. Basically what you're doing is a read action. This is why
it does not belong to `AggregateRootRepository` but should be accomplished with
[a projection or a read model](/docs/reacting-to-events/projections-and-read-models/). A read model does not have to be
persistent, you can totally create an in-memory read model depending on your requirements.

> How do I validate an aggregate against a service?

The aggregate is tasked with maintaining the integrity of the model. Sometimes this requires validation of certain
data against e.g. an external API or any other service. Aggregates should not depend on external services for their
construction. Still they are expected to encapsulate the behaviour of interacting with said services. To enable this,
services can be supplied to the method that represents a particular interaction. Unlike traditional method injection,
these services are not assigned to internal properties. The services are only used in the scope of executing the method.

Let's say we had an aggregate representing a bank account and wanted to validate the provided IBAN against an external API:

```php
<?php

namespace AcmeCompany\AcmeProject;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class AcmeBankAccount implements AggregateRoot
{
    use AggregateRootBehaviour;
        
    public function changeIban(string $iban, IbanValidation $ibanValidation): self
    {
        if (!$ibanValidation->ibanIsValid($iban)) {
            $this->recordThat(new ProvidedIbanWasInvalid($iban));
        } else {
            $this->recordThat(new IbanWasChanged($iban));
        }
    }
}
```

> How do I migrate invalid historic event data?

Let's re-use our `AcmeBankAccount` aggregate from the previous question. Now imagine after two years, you notice that
you should have validated the IBAN data and that your existing aggregates now contain invalid data.
Do not migrate existing events in this case! In event sourcing, events are immutable and must not be tempered with. 

One possible way of handling this business case would be to loop over all the existing aggregates, and fix them by recording
another `IbanWasChanged` event, if you can fix them automatically. Or even better, a `InvalidIbanWasAutomaticallyFixed`
event so that you can easily distinguish later on, what has been changed by e.g. a user or your own business logic.
 