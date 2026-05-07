<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class AggregateRootIdVersionSubscriptionProviderTest extends TestCase
{
    /** @test */
    public function it_gets_aggregate_root_specific_events()
    {
        $idA = DummyAggregateRootId::generate();
        $idB = DummyAggregateRootId::generate();

        $messageRepository = new InMemoryMessageRepository();
        $messageRepository->persist(
            (new Message(new EventStub('1')))->withHeader(Header::AGGREGATE_ROOT_ID, $idA)->withHeader(Header::AGGREGATE_ROOT_VERSION, 1),
            (new Message(new EventStub('2')))->withHeader(Header::AGGREGATE_ROOT_ID, $idB)->withHeader(Header::AGGREGATE_ROOT_VERSION, 1),
        );

        $provider = new AggregateRootIdVersionSubscriptionProvider($messageRepository);
        $messages = $provider->getEventsSinceCheckpoint(AggregateCheckpoint::forAggregateRootId($idA));

        $actualMessages = iterator_to_array($messages, false);
        $this->assertCount(1, $actualMessages);
        $checkpoint = $messages->getReturn();
        $this->assertInstanceOf(AggregateCheckpoint::class, $checkpoint);
        $this->assertEquals(1, $checkpoint->getVersion());
    }
}
