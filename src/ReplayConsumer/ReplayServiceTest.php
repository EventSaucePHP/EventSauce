<?php

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\ReplayConsumer\TestHelpers\InMemoryReplayMessageRepository;
use EventSauce\EventSourcing\ReplayConsumer\TestHelpers\TestReplayableMessageConsumer;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use PHPUnit\Framework\TestCase;

class ReplayServiceTest extends TestCase
{
    /** @test */
    public function it_replays_events_to_the_configured_consumers()
    {
        $messageRepository = new InMemoryReplayMessageRepository();
        $messageRepository->persist(...array_map(
            fn($number) => new Message(EventStub::create($number)),
            range(1, 100))
        );

        $consumer = new TestReplayableMessageConsumer(101);
        $replayService = new ReplayService(
            $messageRepository,
            new SynchronousMessageDispatcher(
                $consumer,
            ),
            10
        );

        $replayService->replay();
        $this->assertEquals(100, $consumer->numberOfMessagesProcessed());
    }

    /** @test */
    public function it_calls_before_replay_when_message_dispatcher_supports_it()
    {
        $messageRepository = new InMemoryReplayMessageRepository();
        $messageRepository->persist(...array_map(
                fn($number) => new Message(EventStub::create($number)),
                range(1, 100))
        );

        $consumer = new TestReplayableMessageConsumer(101);
        $replayService = new ReplayService(
            $messageRepository,
            new ReplayMessageDispatcher(
                new SynchronousMessageDispatcher(
                    $consumer,
                ),
                $consumer,
            ),
            10
        );

        $replayService->replay();
        $this->assertEquals(100, $consumer->numberOfMessagesProcessed());
        $this->assertTrue($consumer->beforeReplayIsCalled());
    }
}