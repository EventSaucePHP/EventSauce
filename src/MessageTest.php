<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;
use EventSauce\Clock\TestClock;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use Throwable;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function accessors(): void
    {
        $event = EventStub::create('some value');
        $initialHeaders = ['initial' => 'header value'];
        $message = new Message($event, $initialHeaders);
        $this->assertSame($event, $message->payload());
        $this->assertEquals($initialHeaders, $message->headers());
    }

    /**
     * @test
     */
    public function accessing_the_version_when_not_set(): void
    {
        $this->expectException(RuntimeException::class);
        (new Message(EventStub::create('v')))->aggregateVersion();
    }

    /**
     * @test
     */
    public function aggregate_root_id_accessor(): void
    {
        $event = EventStub::create('some value');
        $message = new Message($event);
        $this->assertNull($message->aggregateRootId());
        $message = $message->withHeader(Header::AGGREGATE_ROOT_ID, DummyAggregateRootId::generate());
        $this->assertInstanceOf(AggregateRootId::class, $message->aggregateRootId());
    }

    /**
     * @test
     */
    public function time_of_recording_accessor(): void
    {
        $event = EventStub::create('some value');
        $message = new Message($event);
        $timeOfRecording = (new TestClock())->now();
        $message = $message->withHeader(Header::TIME_OF_RECORDING, $timeOfRecording->format('Y-m-d H:i:s.uO'));
        $this->assertInstanceOf(DateTimeImmutable::class, $message->timeOfRecording());
        $this->assertSame($timeOfRecording->format('Y-m-d H:i:s.uO'), $message->timeOfRecording()->format('Y-m-d H:i:s.uO'));
    }

    /**
     * @test
     */
    public function trying_to_resolve_a_time_of_recording_when_the_header_does_not_exist(): void
    {
        $event = EventStub::create('some value');
        $message = new Message($event);

        $this->expectExceptionObject(UnableToResolveTimeOfRecording::fromFormatAndHeader(Message::TIME_OF_RECORDING_FORMAT, ''));

        $message->timeOfRecording();
    }

    /**
     * @test
     */
    public function adding_multiple_headers_to_a_message(): void
    {
        $message = new Message(new stdClass(), ['value' => 1, 'one' => 1]);
        $expectedHeaders = ['value' => 2, 'one' => 1, 'two' => 2];

        $changedMessage = $message->withHeaders(['value' => 2, 'two' => 2]);
        $headers = $changedMessage->headers();

        self::assertEquals($expectedHeaders, $headers);
    }

    /**
     * @test
     */
    public function time_of_recording_is_asserted(): void
    {
        $message = new Message(EventStub::create('this'));

        $this->expectException(Throwable::class);

        $message->timeOfRecording();
    }

    /**
     * @test
     * @dataProvider dbHeaderValues
     */
    public function setting_headers_of_various_types(int|string|array|AggregateRootId|null|bool|float $headerValue): void
    {
        $message = (new Message(new stdClass()));

        $message = $message->withHeader('header', $headerValue);
        $returnedValue = $message->header('header');

        self::assertEquals($headerValue, $returnedValue);
    }

    public function dbHeaderValues(): iterable
    {
        return [
            'int' => [1234],
            'bool' => [false],
            'float' => [0.51],
            'string' => ['string'],
            'scalar_arrar' => [['something' => 'value', 1234]],
            'aggregate_root_id' => [DummyAggregateRootId::generate()],
            'null' => [null],
        ];
    }
}
