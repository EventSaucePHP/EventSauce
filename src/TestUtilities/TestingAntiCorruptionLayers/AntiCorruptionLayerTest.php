<?php

namespace EventSauce\EventSourcing\TestUtilities\TestingAntiCorruptionLayers;

use EventSauce\EventSourcing\AntiCorruptionLayer\AllowAllMessages;
use EventSauce\EventSourcing\AntiCorruptionLayer\AllowMessagesWithPayloadOfType;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageConsumer;
use EventSauce\EventSourcing\AntiCorruptionLayer\AntiCorruptionMessageDispatcher;
use EventSauce\EventSourcing\AntiCorruptionLayer\PassthroughMessageTranslator;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\TestUtilities\AntiCorruptionLayerTestCase;

class AntiCorruptionLayerTest extends AntiCorruptionLayerTestCase
{
    /**
     * @test
     */
    public function it_passes_trough_all_messages()
    {
        $this->givenMessages(
            new Message(new EventA())
        )->dispatchedThrough(
            fn(MessageDispatcher $dispatcher) => new AntiCorruptionMessageDispatcher(
                $dispatcher,
                new PassthroughMessageTranslator(),
                filterBefore: new AllowAllMessages(), // optional
                filterAfter: new AllowAllMessages(), // optional
            )
        )->expectMessages(
            new Message(new EventA())
        );
    }
    /**
     * @test
     */
    public function it_converts_messages_from_a_to_b()
    {
        $this->givenMessages(
            new Message(new EventA('passed value'))
        )->dispatchedThrough(
            fn(MessageDispatcher $dispatcher) => new AntiCorruptionMessageDispatcher(
                $dispatcher,
                new TranslateEventAToEventB(),
                filterBefore: new AllowAllMessages(), // optional
                filterAfter: new AllowAllMessages(), // optional
            )
        )->expectMessages(
            new Message(new EventB('passed value'))
        );
    }

    /**
     * @test
     */
    public function it_filters_out_messages_of_type_a()
    {
        $this->givenMessages(
            new Message(new EventA()),
            new Message(new EventB()),
        )->dispatchedThrough(
            fn(MessageDispatcher $dispatcher) => new AntiCorruptionMessageDispatcher(
                $dispatcher,
                new PassthroughMessageTranslator(),
                filterBefore: new AllowMessagesWithPayloadOfType(EventB::class),
                filterAfter: new AllowAllMessages(),
            )
        )->expectEvents(
            new EventB(),
        );
    }

    /**
     * @test
     */
    public function it_tests_anti_corruption_layer_message_consumer()
    {
        $this->givenMessages(
            new Message(new EventA('value of a'))
        )->consumedThrough(
            fn(MessageConsumer $consumer) => new AntiCorruptionMessageConsumer(
                $consumer,
                new TranslateEventAToEventB(),
                filterBefore: new AllowAllMessages(),
                filterAfter: new AllowAllMessages(),
            )
        )->expectMessages(
            new Message(new EventB('value of a'))
        );
    }

    /**
     * @test
     */
    public function dispatcher_filters_out_messages_of_type_a()
    {
        $this->givenMessages(
            new Message(new EventA()),
            new Message(new EventB('something')),
        )->dispatchedThrough(
            fn(MessageDispatcher $dispatcher) => new AntiCorruptionMessageDispatcher(
                $dispatcher,
                new PassthroughMessageTranslator(),
                filterBefore: new AllowMessagesWithPayloadOfType(EventB::class),
                filterAfter: new AllowAllMessages(),
            )
        )->expectEvents(
            new EventB('something')
        );
    }
}
