---
permalink: /docs/advanced/rebuilding-projections/
title: Rebuilding Projections
published_at: 2018-03-13
updated_at: 2018-03-13
---

Rebuilding projections is one of the more complicated subjects in
event sourcing. EventSauce takes a uncommon approach to tackle this
problem: it **does not** tackle the problem.

Generic rebuild tooling is very complex and imposes some additional
constraints. Even then generic tooling will probably cover about 80%
of the use-cases.

The way that EventSauce is designed allows for very easy extension.
Implementing your own `MessageRepository` and/or `MessageDispatcher` is
done in a matter of minutes. Keeping this interface simple was a very
important decision. It means you're able to take **full control** of it
when (and&nbsp;if) needed. 

