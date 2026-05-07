<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class OffsetStreamSubscriptionProviderTest extends TestCase
{
    /** @test */
    public function it_gets_the_events_from_the_repository_since_origin()
    {
        $messageRepository = new InMemoryMessageRepository();
        $messageRepository->persist(
            new Message(new EventStub('1')),
            new Message(new EventStub('2')),
        );

        $provider = new OffsetStreamSubscriptionProvider($messageRepository);
        $messages = $provider->getEventsSinceCheckpoint(OffsetCheckpoint::forOffset(0), 100);

        $actualMessages = iterator_to_array($messages, false);
        $this->assertCount(2, $actualMessages);
        $checkpoint = $messages->getReturn();
        $this->assertInstanceOf(OffsetCheckpoint::class, $checkpoint);
    }


}
