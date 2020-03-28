---
permalink: /docs/changelog/
title: Changelog
published_at: 2018-03-14
updated_at: 2019-12-10
---

## Unreleased

### Changes

- The `EventSauce\EventSourcing\Consumer` interface was renamed to `EventSauce\EventSourcing\MessageConsumer`.

### Added

- `Message::timeOfRecording` was added.

## 0.8.1 - 2020-04-05

### New Features

- Allow `ramsey/uuid` version 3 and 4.

## 0.8.0 - 2019-12-21

### Added

- `EventDispatcher` was added:<br/>
  Dispatch events with ease! The default MessageDispatchingEventDispatcher allows you to
  dispatched events, which dispatches Messages on your regular MessageDispatcher.

### Breaking Changes

- The method `::timeZone(): DateTimeZone` was added to the `Clock` interface.
- `TestClock::moveForward(DateInterval $interval): void` was added:<br/>
  The `moveForward` was introduces to make moving the test clock forward easier. Read
  [how to use the SystemClock and the TestClock](/docs/utilities/clock/).
  <br/>

## 0.7.0

### Added

- Snapshotting 🤩
- Code Generation supports user defined interfaces for generated classes.
- A new `EventConsumer` base-class is provided to simplify event consumption.

### Improvements

- Aggregate version handling is now more accurate (inaccuracies could happen when reducing streams in upcasting).

### Breaking Changes

- Message repositories are now expected to return the aggregate version as the `Generator` return value.
- Message repositories must now implement the `retrieveAllAfterVersion` method.
- Many things have return types now 👍

See the [upgrade guide to 0.7.0](/docs/upgrading/to-0-7-0).

## 0.6.0

### Breaking Changes

- Event serialization is now converted to payload serialization. Generated commands now use the same serialization for easier tracing and logging.
- Aggregate root behaviour now has a private constructor.

See the [upgrade guide to 0.6.0](/docs/upgrading/to-0-6-0).

## 0.5.1

### Breaking Changes

Test helpers (the ::withX methods) are now immutable.

## 0.5.0

### Breaking Changes

The abstract `BaseAggregateRoot` has now been removed and all the traits have
been collapsed into one. This trait has been moved to the root namespace.

## Fixed

* Multiple interactions and intermediate persisting of aggregates now has correct
  versioning of messages.

## 0.4.0

### Fixed

* Code generation now handles types and type aliases better.

## 0.3.1

### Dependencies

* symfony/yaml now allows ^3.2\|^4.0

## 0.3.0

### Breaking Changes

* The `AggregateRootRepository` is now an interface. The default implementation
  is the `ConstructingAggregateRootRepository`.
* The `Event` interface is removed. A new `SerializableEvent` interface is provided
  to aid the default serializers. If you use the default serializers your events
  must implement this interface. The methods are the same as the `Event` interface,
  so effectively it's an in-place replacement.
* The `CodeDumper` is changed to ensure code now implements the `SerializableEvent`
  interface.
* The `AggregateRootTestCase` now allows you to overwrite the `aggregateRootRepository`
  method for if/when you have a custom implementation and still want all the benefits
  of the default test tooling.

## 0.2.2

### Altered

* The Header::AGGREGATE_ROOT_ID is no longer converted to string in the default decorator but in the serializer.
* The Header::AGGREGATE_ROOT_ID_TYPE is now set in the serializer.

## 0.2.1

### Improved

* The `CodeDumper` now generated prettier code.

## 0.2.0

### BC Breaks

* The `PointInTime` related properties of the `Event` interface are
  removed. The `DefaultHeadersDecorator` now ensures all events receive
  a `Header::TIME_OF_RECORDING` headers.
* The `AggregateRoot` now keeps track of a version, the `ConstructionBehaviour`
  trait has been updated to reflect this and shows how it's implemented.
* The `AggregateRootTestCase` now requires you to implement the `newAggregateRootId`
  method to be able to return a stable aggregate root id from the
  `aggregateRootId` method.
  
## 0.1.2

### Added

* The `MessageDispatcherChain` is introduced to be able to chain multiple
  dispatchers together. This allows users to compose a dispatching system
  that combined synchronous and a-synchronous message handling.
  
## 0.1.1

### Added

* The `BaseAggregateRoot` behaviour is now extracted into traits so you can
  choice to implement certain concerns yourself without overriding methods.
