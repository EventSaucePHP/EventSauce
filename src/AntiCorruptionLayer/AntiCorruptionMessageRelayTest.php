<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\CollectingMessageDispatcher;
use EventSauce\EventSourcing\Message;
use PHPStan\Testing\TestCase;

use function array_map;

class AntiCorruptionMessageRelayTest extends TestCase
{
    private CollectingMessageDispatcher $destinationMessageDispatcher;
    private MessageFilter $beforeFilter;
    private MessageFilter $afterFilter;
    private MessageTranslator $translator;

    protected function setUp(): void
    {
        $this->destinationMessageDispatcher = new CollectingMessageDispatcher();
        $this->beforeFilter = new AlwaysAllowingMessageFilter();
        $this->afterFilter = new AlwaysAllowingMessageFilter();
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
        $relay = $this->messageRelay();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        $relay->dispatch(...$messages);
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public function dpNoFilterNoTransform(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPrivateEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], [new StubExcludedEvent('yes')]];
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
        $relay = $this->messageRelay();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        $relay->dispatch(...$messages);
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
        $relay = $this->messageRelay();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        $relay->dispatch(...$messages);
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
     * @dataProvider dpTranslateFromPrivateToPublic
     */
    public function transformation_private_to_public(
        array $incoming,
        array $expected,
    ): void
    {
        $this->translator = new StubTranslatePrivateToPublic();
        $relay = $this->messageRelay();
        $messages = array_map(fn(object $o) => new Message($o), $incoming);

        $relay->dispatch(...$messages);
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public function dpTranslateFromPrivateToPublic(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], [new StubExcludedEvent('yes')]];
    }

    private function messageRelay(): AntiCorruptionMessageRelay
    {
        return new AntiCorruptionMessageRelay(
            $this->destinationMessageDispatcher,
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
        return $this->destinationMessageDispatcher->collectedPayloads();
    }
}
