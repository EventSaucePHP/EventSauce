<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\ReplayConsumer\TestHelpers\TestReplayableMessageConsumer;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use PHPUnit\Framework\TestCase;

class ReplayMessageDispatcherTest extends TestCase
{
    /** @test */
    public function it_calls_before_replay_on_message_handler(): void
    {
        $consumer = new TestReplayableMessageConsumer(1);
        $dispatcher = new ReplayMessageDispatcher(
            new SynchronousMessageDispatcher(),
            $consumer
        );

        $dispatcher->beforeReplay();
        $this->assertTrue($consumer->beforeReplayIsCalled());
    }
}
