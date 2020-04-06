---
permalink: /docs/advanced/database-structure/
title: Database Structure
published_at: 2019-08-01
updated_at: 2019-12-21
---

Storing events for reconstitution should be done in any type queryable data store. It needs
to be queryable because you'll want to fetch all events relevant to only _one_ aggregate root.
There are multiple ways this can be approached. We'll explore 4 ways:

1. All events from your application in one table.
2. All events from a type of aggregate stored in their own table.
3. All events belonging to a particular aggregate in their own table.
4. Storing all events from a document in document (NoSQL)

## All events in one table.

Storing all events in one table is a very simple approach. In this approach the table to
store events in will be shared by multiple different types of event-sourced models. A table
like this will be called something along the lines of "domain_messages".

### Pro's

* Easy to setup.
* Good for small amounts of events.

### Con's

* Difficult to scale.
* Less easy to inspect (more to look through).
* Difficult to optimize for special cases.
* Cleanup is difficult.

## All events in table per aggregate.

Storing events per aggregate type is one step up from using a single table. Generally this is
advised when starting out. It's relatively simple to setup and simple to maintain over time. In
this approach the there are multiple tables named like "booking_messages" and "ordering_messages".

The prefix (or suffix) is named after the aggregate root.

### Pro's

* Also easy to setup.
* Good for small amounts of events.
* For a moderate amount of events.
* Special querying can be easily facilitated.
* Migrating messages is easier (no filtering, just migrate the entire data-set)
* Easy to maintain from a DB perspective.

### Con's

* A little more setup than a single table.
* Cleanups are less difficult/risky, but still require a high attention to detail.

## Table per aggregate identifier.

In this approach the messages will be assigned to a table per aggregate identifier. Tables will be
create dynamically. Table names generally follow a naming scheme consisting of the aggregate type
and identifier, like "orders_[order process id]".

### Pro's

* Smallest indexes of any SQL-based approach, the table is basically an index.
* Message reconstitution for domains with long lasting processes or entities is fast.
* Easy cleanup on aggregate level, simply delete the table.

### Con's

* Maintenance is hard (think of dynamic migration strategies).
* Unlimited amount of tables can make debugging a lot harder (use specialized tooling).
* Database rights need to include those to create tables.
* Other database migrations need to exclude these tables (think of doctrine schema).
* Feeding dispatcher historical data is a complex operation.

## Using a single document.

In this approach a NoSQL database is used where the unit of storage is singular. Using a storage
like MongoDB allows you to store a collection of JSON objects inside another structure. You can
atomically add new entries using a `$push` operation.

### Pro's

* Simple singular model for per aggregate.
* NoSQL databases can scale well.
* Retrieve all events in one operation and "row".

### Con's

* Required a NoSQL database (although MySQL and Postgres also have similar features).
* Creating snapshots is harder when you want incremental snapshots.
