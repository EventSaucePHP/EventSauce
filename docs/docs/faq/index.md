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