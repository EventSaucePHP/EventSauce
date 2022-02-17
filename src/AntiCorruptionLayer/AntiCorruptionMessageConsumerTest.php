<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters\AllowAllMessages;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters\AllowMessagesWithPayloadOfType;
use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters\MessageFilter;
use EventSauce\EventSourcing\AntiCorruptionLayer\Translators\MessageTranslator;
use EventSauce\EventSourcing\AntiCorruptionLayer\Translators\PassthroughMessageTranslator;
use EventSauce\EventSourcing\CollectingMessageConsumer;
use EventSauce\EventSourcing\Message;
use PHPStan\Testing\TestCase;
use function array_map;

class AntiCorruptionMessageConsumerTest extends TestCase
{
    private CollectingMessageConsumer $destinationMessageConsumer;
    private MessageFilter $beforeFilter;
    private MessageFilter $afterFilter;
    private MessageTranslator $translator;

    protected function setUp(): void
    {
        $this->destinationMessageConsumer = new CollectingMessageConsumer();
        $this->beforeFilter = new AllowAllMessages();
        $this->afterFilter = new AllowAllMessages();
        $this->translator = new PassthroughMessageTranslator();
    }

    /**
     * @test
     * @dataProvider dpNoFilterNoTransform
     */
    public function when_no_filters_and_transformation_happens_messages_are_always_relayed(
        array $incoming,
        array $expected,
    ): void
    {
        $consumer = $this->messageConsumer();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        foreach ($messages as $message) {
            $consumer->handle($message);
        }
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public function dpNoFilterNoTransform(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPrivateEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], [new StubExcludedEvent('yes')]];
        yield [[new StubPublicEvent('yes'), new StubPrivateEvent('yes')], [new StubPublicEvent('yes'), new StubPrivateEvent('yes')]];
    }

    /**
     * @test
     * @dataProvider dpFilterExcludedNoTransform
     */
    public function no_transformation_filter_out_excluded_payloads_before_transformation(
        array $incoming,
        array $expected,
    ): void
    {
        $this->beforeFilter = new StubFilterExcludedMessages();
        $consumer = $this->messageConsumer();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        foreach ($messages as $message) {
            $consumer->handle($message);
        }
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    /**
     * @test
     * @dataProvider dpFilterExcludedNoTransform
     */
    public function no_transformation_filter_out_excluded_payloads_after_transformation(
        array $incoming,
        array $expected,
    ): void
    {
        $this->afterFilter = new StubFilterExcludedMessages();
        $consumer = $this->messageConsumer();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        foreach ($messages as $message) {
            $consumer->handle($message);
        }
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public function dpFilterExcludedNoTransform(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPrivateEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], []];
    }

    /**
     * @test
     * @dataProvider dpFilterAllButPublicAndPricate
     */
    public function no_transformation_filter_all_but_public_and_private_payloads_after_transformation(
        array $incoming,
        array $expected,
    ): void
    {
        $this->afterFilter = new AllowMessagesWithPayloadOfType(StubPublicEvent::class, StubPrivateEvent::class);
        $consumer = $this->messageConsumer();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        foreach ($messages as $message) {
            $consumer->handle($message);
        }
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public function dpFilterAllButPublicAndPricate(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPublicEvent('yes'), new StubExcludedEvent('no')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPrivateEvent('yes')]];
        yield [[new StubPrivateEvent('yes'), new StubExcludedEvent('no')], [new StubPrivateEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], []];
    }

    /**
     * @test
     * @dataProvider dpTranslateFromPrivateToPublic
     */
    public function transformation_private_to_public(
        array $incoming,
        array $expected,
    ): void
    {
        $this->translator = new StubTranslatePrivateToPublic();
        $consumer = $this->messageConsumer();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        foreach ($messages as $message) {
            $consumer->handle($message);
        }
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public function dpTranslateFromPrivateToPublic(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], [new StubExcludedEvent('yes')]];
    }

    private function messageConsumer(): AntiCorruptionMessageConsumer
    {
        return new AntiCorruptionMessageConsumer(
            $this->destinationMessageConsumer,
            $this->translator,
            $this->beforeFilter,
            $this->afterFilter,
        );
    }

    /**
     * @return array
     */
    private function dispatchedPayloads(): array
    {
        return $this->destinationMessageConsumer->collectedPayloads();
    }
}
