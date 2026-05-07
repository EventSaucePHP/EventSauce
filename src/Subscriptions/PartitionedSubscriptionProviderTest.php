<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class PartitionedSubscriptionProviderTest extends TestCase
{
    /** @test */
    public function it_can_get_a_partition_of_a_stream()
    {

        $messageRepository = new InMemoryMessageRepository();
        $messageRepository->persist(
            (new Message(new EventStub('1')))->withHeader('test-partition-key', 'partition-1'),
            (new Message(new EventStub('1')))->withHeader('test-partition-key', 'partition-2'),
        );

        $provider = new PartitionedSubscriptionProvider($messageRepository, new HeaderPartitioner('test-partition-key'));
        $messages = $provider->getEventsSinceCheckpoint(PartitionedCheckpoint::fromOrigin('partition-1'));

        $actualMessages = iterator_to_array($messages, false);
        $this->assertCount(1, $actualMessages);
        $checkpoint = $messages->getReturn();
        $this->assertInstanceOf(PartitionedCheckpoint::class, $checkpoint);

        // Cursor has offset of 2, since filtered out messages need to be counted in the stream offset
        $this->assertEquals(2, $checkpoint->getOffset());

    }
}
