<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayingMessages;

use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\OffsetCursor;
use PHPUnit\Framework\TestCase;

class ReplayMessagesTest extends TestCase
{
    /**
     * @test
     */
    public function replaying_multiple_pages(): void
    {
        $messageRepository = new InMemoryMessageRepository();
        $messageRepository->persist([
            new Message(new EventStub('1')),
            new Message(new EventStub('2')),
            new Message(new EventStub('3')),
            new Message(new EventStub('4')),
            new Message(new EventStub('5')),
            new Message(new EventStub('6')),
            new Message(new EventStub('7')),
            new Message(new EventStub('8')),
            new Message(new EventStub('9')),
            new Message(new EventStub('10')),
        ]);

        $replayer = new ReplayMessages(
            $messageRepository,
            $consumer = new StubReplayConsumer(),
        );

        $cursor = OffsetCursor::fromStart()->withLimit(2);

        while (true) {
            $result = $replayer->replayBatch($cursor);
            $cursor = $result->cursor();
            $cursor = OffsetCursor::fromString($cursor->toString());

            if ($result->messagesHandled() === 0) {
                break;
            }
        }

        self::assertTrue($consumer->beforeTriggered);
        self::assertTrue($consumer->afterTriggered);
        self::assertEquals(10, $consumer->messagesHandled);
    }
}
