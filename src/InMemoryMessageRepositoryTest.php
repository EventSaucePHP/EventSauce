<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

use function iterator_to_array;

class InMemoryMessageRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function getting_the_first_page_for_pagination(): void
    {
        $repository = new InMemoryMessageRepository();

        $repository->persist(
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
        );

        $messages = $repository->paginate(5);
        $actualMessages = iterator_to_array($messages, false);
        $cursor = $messages->getReturn();

        self::assertEquals([
            new Message(new EventStub('1')),
            new Message(new EventStub('2')),
            new Message(new EventStub('3')),
            new Message(new EventStub('4')),
            new Message(new EventStub('5')),
        ], $actualMessages);

        self::assertInstanceOf(PaginationCursor::class, $cursor);
    }

    /**
     * @test
     */
    public function getting_a_subsequent_page(): void
    {
        $repository = new InMemoryMessageRepository();

        $repository->persist(
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
        );

        $messages = $repository->paginate(5);
        // consume messages to ensure return value is generated
        iterator_to_array($messages, false);
        $cursor = $messages->getReturn();

        $messages = $repository->paginate(5, $cursor);
        $actualMessages = iterator_to_array($messages, false);

        self::assertEquals([
           new Message(new EventStub('6')),
           new Message(new EventStub('7')),
           new Message(new EventStub('8')),
           new Message(new EventStub('9')),
           new Message(new EventStub('10')),
        ], $actualMessages);
    }
}
