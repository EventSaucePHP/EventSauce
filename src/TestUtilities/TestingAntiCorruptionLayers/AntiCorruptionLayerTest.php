<?php

namespace EventSauce\EventSourcing\TestUtilities\TestingAntiCorruptionLayers;

use EventSauce\EventSourcing\AntiCorruptionLayer\AllowAllMessages;
use EventSauce\EventSourcing\AntiCorruptionLayer\AllowMessagesWithPayloadOfType;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageConsumer;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageDispatcher;
use EventSauce\EventSourcing\AntiCorruptionLayer\PassthroughMessageTranslator;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\TestUtilities\AntiCorruptionLayerTestCase;

class AntiCorruptionLayerTest extends AntiCorruptionLayerTestCase
{
    /** @test */
    public function it_passes_trough_all_messages()
    {
        $this->given(
            new Message(new EventA())
        )->passedTroughAntiCorruptionMessageDispatcher(
            new AntiCorruptionMessageDispatcher(
                $this->getDestinationDispatcher(),
                new PassthroughMessageTranslator(),
                filterBefore: new AllowAllMessages(), // optional
                filterAfter: new AllowAllMessages(), // optional
            )
        )->then(
            new Message(new EventA())
        );
    }

    /** @test */
    public function it_filters_out_messages_of_type_a()
    {
        $this->given(
            new Message(new EventA())
        )->passedTroughAntiCorruptionMessageDispatcher(
            new AntiCorruptionMessageDispatcher(
                $this->getDestinationDispatcher(),
                new PassthroughMessageTranslator(),
                filterBefore: new AllowMessagesWithPayloadOfType(),
                filterAfter: new AllowAllMessages(),
            )
        )->then(
        );
    }

    /** @test */
    public function it_tests_anti_corruption_layer_message_consumer()
    {
        $this->given(
            new Message(new EventA())
        )->passedTroughAntiCorruptionMessageConsumer(
            new AntiCorruptionMessageConsumer(
                $this->getDestinationConsumer(),
                new PassthroughMessageTranslator(),
                filterBefore: new AllowAllMessages(),
                filterAfter: new AllowAllMessages(),
            )
        )->then(
            new Message(new EventA())
        );
    }

    /** @test */
    public function dispatcher_filters_out_messages_of_type_a()
    {
        $this->given(
            new Message(new EventA())
        )->passedTroughAntiCorruptionMessageDispatcher(
            new AntiCorruptionMessageDispatcher(
                $this->getDestinationDispatcher(),
                new PassthroughMessageTranslator(),
                filterBefore: new AllowMessagesWithPayloadOfType(),
                filterAfter: new AllowAllMessages(),
            )
        )->then(
        );
    }
}
