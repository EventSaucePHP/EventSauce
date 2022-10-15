<?php
declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAntiCorruptionLayers;

use EventSauce\EventSourcing\AntiCorruptionLayer\AllowMessagesWithPayloadOfType;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageDispatcher;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\TestUtilities\AntiCorruptionLayerTestCase;

class OutboundAntiCorruptionTest extends AntiCorruptionLayerTestCase
{
    protected function antiCorruptionDispatcher(MessageDispatcher $dispatcher): AntiCorruptionMessageDispatcher
    {
        return new AntiCorruptionMessageDispatcher(
            $dispatcher,
            new TranslateEventAToEventB(),
            filterAfter: new AllowMessagesWithPayloadOfType(EventB::class),
        );
    }

    /**
     * @test
     */
    public function when_event_a_is_disapatched_event_b_is_consumed(): void
    {
        $this->givenEvents(new EventA('value of a'))
            ->expectEvents(new EventB('value of a'));
    }
}