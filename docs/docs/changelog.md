---
permalink: /docs/changelog/
title: Changelog
---

## 2.3.1 - 2022-08-28

### Changed

- Allow eventsauce/object-hydrator version 0.5

## 2.3.0 - 2022-07-18

- Added object-mapper based serialization method (#175)
- Allowed for event consumer strategies (#176)

## 2.2.1 - 2022-05-28

### Fixed

- Fixed visibility for serialization overrides in aggregate root test case

## 2.2.0 - 2022-05-28

### Added

- Allow serialization overrides in aggregate root test case.

## 2.1.0 - 2022-04-16

### Added

- Customisable event payload assertions (#170) by @Robertbaelde
- Set the aggregate ID in the message consumer testcase (#172) by @Robertbaelde

## 2.0.0 - 2022-04-03

### Changed

- Message::payload introduced as a replacement for Message::event.
- Message::event is deprecated.
- SerializablePayload::fromPayload return type is now `static`.
- Message::withTimeOfRecording now accepts a format parameter.
- DefaultHeadersDecorator accepts a timeOfRecordingFormat constructor parameter.
- AggregateRootId::fromString return type is now `static`.

## 1.4.1 - 2022-02-25

### Changed

- [CodeGeneration] Allow symfony/yaml version 6

## 1.4.0 - 2022-02-17

### Added

- [NEW] AntiCorruptionLayer module, containing a consumer, dispatcher, and relay that filter and translate messages.
- for AggregateRootBehaviour: extracted createNewInstance to allow construction overrides.
- CollectingMessageConsumer, useful for testing things that invoke or decorate consumers
- for CollectingMessageDispatcher, added collectedPayloads for convenience during test assertions

### Changed

- for MessageConsumerThatSerializesMessages, this implementation now serialized from and to JSON for increased test confidence.

## 1.3.0

### Added

- ExplicitlyMappedClassNameInflector: a explicitly mapped class name inflector, allowing specific mapping class names from and to event types

## 1.2.1

### Added

- Added a deprecated Consumer interface for easier upgrade from 0.8

## 1.2.0

### Added

- Added a new header AGGREGATE_ROOT_TYPE, which is added by the default aggregate root repository and exposed via a
  getter on the `Message` class.

## 1.1.0

### Added

- [Serialization] Added a MySQL8DateFormatting serialization decorator to support MySQL 8 dates.

## 1.0.3

### Fixed

- Added missing scalar types for message header values (array/float/bool).

## 1.0.2

### Fixed

- Loosened the exception checking to be more in line with PHPUnit's exception comparison.

## 1.0.1

### Fixed

- Aggregate root version is now correctly incremented per event.

## 1.0.0

### Changes

- The `EventSauce\EventSourcing\Consumer` interface was renamed to `EventSauce\EventSourcing\MessageConsumer`.
- The dependency on `ramsey/uuid` was removed.
- The `EventSauce\EventSourcing\UuidAggregateRootId` was removed.
- The `CodeGeneration` module was extracted into a separate package `eventsauce/code-generation`.
- The test utilities were extracted into a separate package `eventsauce/test-utilities`.
- The clock module was extracted into a separate package `eventsauce/clock`.
- The serialization and upcasting is no longer generator based and now maps 1:1.

### Added

- `Message::timeOfRecording` was added.

## 0.8.2 - 2020-11-01

### Changes

- Support PHP 8
- Added typehints
- Cleaned up shipped artifact

## 0.8.1 - 2020-04-05

### New Features

- Allow `ramsey/uuid` version 3 and 4.

### Added

- `Message::timeOfRecording` was added.

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
