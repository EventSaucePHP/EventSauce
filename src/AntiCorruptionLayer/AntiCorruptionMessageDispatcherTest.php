<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\CollectingMessageDispatcher;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

use function array_map;

class AntiCorruptionMessageDispatcherTest extends TestCase
{
    private CollectingMessageDispatcher $destinationMessageDispatcher;
    private MessageFilter $beforeFilter;
    private MessageFilter $afterFilter;
    private MessageTranslator $translator;

    protected function setUp(): void
    {
        $this->destinationMessageDispatcher = new CollectingMessageDispatcher();
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
    ): void {
        $dispatcher = $this->messageDispatcher();
        $messages = array_map(fn (object $o) => new Message($o), $incoming);

        $dispatcher->dispatch(...$messages);
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public static function dpNoFilterNoTransform(): iterable
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
    ): void {
        $this->beforeFilter = new StubFilterExcludedMessages();
        $dispatcher = $this->messageDispatcher();
        $messages = array_map(fn (object $o) => new Message($o), $incoming);

        $dispatcher->dispatch(...$messages);
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
    ): void {
        $this->afterFilter = new StubFilterExcludedMessages();
        $dispatcher = $this->messageDispatcher();
        $messages = array_map(fn (object $o) => new Message($o), $incoming);

        $dispatcher->dispatch(...$messages);
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public static function dpFilterExcludedNoTransform(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPrivateEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], []];
    }

    /**
     * @test
     * @dataProvider dpFilterAllButPublicAndPrivate
     */
    public function no_transformation_filter_all_but_public_and_private_payloads_after_transformation(
        array $incoming,
        array $expected,
    ): void {
        $this->afterFilter = new AllowMessagesWithPayloadOfType(StubPublicEvent::class, StubPrivateEvent::class);
        $dispatcher = $this->messageDispatcher();
        $messages = array_map(fn (object $o) => new Message($o), $incoming);

        $dispatcher->dispatch(...$messages);
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public static function dpFilterAllButPublicAndPrivate(): iterable
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
    ): void {
        $this->translator = new StubTranslatePrivateToPublic();
        $dispatcher = $this->messageDispatcher();
        $messages = array_map(fn (object $o) => new Message($o), $incoming);

        $dispatcher->dispatch(...$messages);
        $dispatchedEvents = $this->dispatchedPayloads();

        $this->assertEquals($expected, $dispatchedEvents);
    }

    public static function dpTranslateFromPrivateToPublic(): iterable
    {
        yield [[new StubPublicEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubPrivateEvent('yes')], [new StubPublicEvent('yes')]];
        yield [[new StubExcludedEvent('yes')], [new StubExcludedEvent('yes')]];
    }

    private function messageDispatcher(): AntiCorruptionMessageDispatcher
    {
        return new AntiCorruptionMessageDispatcher(
            $this->destinationMessageDispatcher,
            $this->translator,
            $this->beforeFilter,
            $this->afterFilter,
        );
    }

    private function dispatchedPayloads(): array
    {
        return $this->destinationMessageDispatcher->collectedPayloads();
    }
}
