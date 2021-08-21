<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\DefaultHeadersDecorator;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class MySQL8DateFormattingTest extends TestCase
{
    /**
     * @test
     */
    public function converts_message_to_mysql_8_compatible_string(): void
    {
        $message = (new Message(new EventStub('value')))->withHeader(Header::TIME_OF_RECORDING, '2021-08-20 16:00:44.182717+0400');
        $serializer = new MySQL8DateFormatting(new ConstructingMessageSerializer());

        $payload = $serializer->serializeMessage($message);
        $timeStamp = $payload['headers'][Header::TIME_OF_RECORDING];

        self::assertEquals('2021-08-20 16:00:44.182717+04:00', $timeStamp);
    }
    /**
     * @test
     */
    public function converts_payload_with_mysql_8_compatible_string_back_to_eventsauce_format(): void
    {
        $serializer = new MySQL8DateFormatting(new ConstructingMessageSerializer());
        $payload = $serializer->serializeMessage((new DefaultHeadersDecorator())->decorate(new Message(new EventStub('value'))));
        $payload['headers'][Header::TIME_OF_RECORDING] = '2021-08-20 16:00:44.182717+04:00';

        $message = $serializer->unserializePayload($payload);
        $timeStamp = $message->timeOfRecording()->format(Message::TIME_OF_RECORDING_FORMAT);

        self::assertEquals('2021-08-20 16:00:44.182717+0400', $timeStamp);
    }
}
