<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\Translators;

use EventSauce\EventSourcing\AntiCorruptionLayer\StubExcludedEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPrivateEvent;
use EventSauce\EventSourcing\AntiCorruptionLayer\StubPublicEvent;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class MessageTranslatorPerPayloadTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider dpTransformationExamples
     */
    public function messages_are_routed_to_the_right_translator(Message $input, Message $expected): void
    {
        $translators = [
            StubPublicEvent::class => new class implements MessageTranslator {
                public function translateMessage(Message $message): Message
                {
                    return $message->withHeader('x', 'public');
                }
            },
            StubPrivateEvent::class => new class implements MessageTranslator {
                public function translateMessage(Message $message): Message
                {
                    return $message->withHeader('x', 'private');
                }
            },
        ];

        $translator = new MessageTranslatorPerPayloadType($translators);

        $actual = $translator->translateMessage($input);

        self::assertEquals($expected, $actual);
    }

    public function dpTransformationExamples(): iterable
    {
        yield [new Message(new StubPublicEvent('yes')), new Message(new StubPublicEvent('yes'), ['x' => 'public'])];
        yield [new Message(new StubPrivateEvent('yes')), new Message(new StubPrivateEvent('yes'), ['x' => 'private'])];
        yield [new Message(new StubExcludedEvent('yes')), new Message(new StubExcludedEvent('yes'))];
    }
}
