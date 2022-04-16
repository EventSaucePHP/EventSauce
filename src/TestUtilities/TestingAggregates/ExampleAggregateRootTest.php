<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use DateTimeImmutable;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;
use EventSauce\EventSourcing\TestUtilities\FailedToDetectExpectedException;
use LogicException;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @method DummyAggregateRootId aggregateRootId()
 */
class ExampleAggregateRootTest extends AggregateRootTestCase
{
    protected function aggregateRootClassName(): string
    {
        return DummyAggregate::class;
    }

    protected function commandHandler(AggregateRootRepository $repository): DummyCommandHandler
    {
        return new DummyCommandHandler($repository);
    }

    /**
     * @test
     */
    public function test_static_initiator(): void
    {
        $this->when(new InitiatorCommand($this->aggregateRootId()));
        $this->then(new AggregateWasInitiated());
    }

    /**
     * @test
     */
    public function executing_a_command_successfully(): void
    {
        $aggregateRootId = $this->aggregateRootId();
        $this->when(new PerformDummyTask($aggregateRootId));
        $this->then(new DummyTaskWasExecuted());
    }

    /**
     * @test
     */
    public function the_current_time_is_exposed(): void
    {
        $this->assertInstanceOf(DateTimeImmutable::class, $this->currentTime());
    }

    /**
     * @test
     */
    public function asserting_nothing_happened(): void
    {
        $aggregateRootId = $this->aggregateRootId();
        $this->when(new IgnoredCommand($aggregateRootId));
        $this->thenNothingShouldHaveHappened();
    }

    /**
     * @test
     */
    public function expecting_exceptions(): void
    {
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()))
            ->expectToFail(new DummyException());
    }

    /**
     * @test
     */
    public function not_expecting_exceptions(): void
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()));
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_the_wrong_exception(): void
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()))
            ->expectToFail(new LogicException());
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_an_exception_but_not_detecting_one(): void
    {
        $this->expectException(FailedToDetectExpectedException::class);
        $this->expectToFail(new LogicException());
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function setting_preconditions(): void
    {
        $id = $this->aggregateRootId();
        $this->given(new DummyIncrementingHappened(1))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened(2));
    }

    /**
     * @test
     */
    public function setting_preconditions_from_other_aggregates(): void
    {
        $id = $this->aggregateRootId();
        $this->on(DummyAggregateRootId::generate())
            ->stage(new DummyIncrementingHappened(10))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened(1));
    }

    /**
     * @test
     */
    public function messages_have_a_sequence(): void
    {
        $id = $this->aggregateRootId();
        $this->given(new DummyIncrementingHappened(10))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened(11));

        /** @var Message[] $messages */
        $messages = [];

        /** @var Message $message */
        foreach ($this->messageRepository->retrieveAll($id) as $message) {
            $messages[] = $message;
        }

        $this->assertContainsOnlyInstancesOf(Message::class, $messages);
        $this->assertEquals(1, $messages[0]->header(Header::AGGREGATE_ROOT_VERSION));
        $this->assertEquals(2, $messages[1]->header(Header::AGGREGATE_ROOT_VERSION));
    }

    /** @test */
    public function it_fails_when_expected_event_is_not_recorded(): void
    {
        $this->then(new DummyIncrementingHappened(1));
        $this->expectAssertionToFail();
    }

    /** @test */
    public function it_fails_when_event_is_not_equal_to_the_expected_event(): void
    {
        $this->given(new DummyIncrementingHappened(1))
            ->when(new DummyIncrementCommand($this->aggregateRootId()))
            ->then(new DummyIncrementingHappened(1));
        $this->expectAssertionToFail();
    }

    /** @test */
    public function it_can_assert_event_class_without_doing_a_exact_comparison(): void
    {
        $this->when(new PerformDummyTask($this->aggregateRootId()));
        $this->then(
            $this->expectEventOfType(DummyTaskWasExecuted::class)
        );
    }

    /** @test */
    public function it_fails_when_different_event_was_fired_than_expected(): void
    {
        $this->when(new PerformDummyTask($this->aggregateRootId()));
        $this->then(
            $this->expectEventOfType(AggregateWasInitiated::class)
        );
        $this->expectAssertionToFail();
    }

    /** @test */
    public function it_can_test_parts_of_the_events_data(): void
    {
        $this->when(new DummyIncrementCommand($this->aggregateRootId()));
        $this->then(
            $this->expectEventOfType(DummyIncrementingHappened::class)->toMatch(function (DummyIncrementingHappened $dummyIncrementingHappened): void {
                $this->assertEquals(1, $dummyIncrementingHappened->number());
            })
        );
    }

    /** @test */
    public function it_fails_when_callback_returns_false(): void
    {
        $this->when(new DummyIncrementCommand($this->aggregateRootId()));
        $this->then(
            $this->expectEventOfType(DummyIncrementingHappened::class)->toMatch(function (DummyIncrementingHappened $dummyIncrementingHappened): bool {
                return false;
            })
        );
        $this->expectAssertionToFail();
    }

    /** @test */
    public function it_passes_when_callback_returns_true(): void
    {
        $this->when(new DummyIncrementCommand($this->aggregateRootId()));
        $this->then(
            $this->expectEventOfType(DummyIncrementingHappened::class)->toMatch(function (DummyIncrementingHappened $dummyIncrementingHappened): bool {
                return true;
            })
        );
    }

    /** @test */
    public function it_can_assert_events_on_callback_only()
    {
        $this->when(new DummyIncrementCommand($this->aggregateRootId()));
        $this->then(
            $this->expectEventToMatch(function (DummyIncrementingHappened $dummyIncrementingHappened): bool {
                return true;
            })
        );
    }

    protected function handle(DummyCommand $command): void
    {
        $commandHandler = $this->commandHandler($this->repository);
        $commandHandler->handle($command);
    }

    protected function newAggregateRootId(): AggregateRootId
    {
        return DummyAggregateRootId::generate();
    }

    private function expectAssertionToFail(): void
    {
        $failed = false;
        try {
            $this->assertScenario();
        } catch (ExpectationFailedException $e) {
            $failed = true;
        }
        $this->assertTrue($failed, "expected to fail, but the test didn't fail");
    }
}
