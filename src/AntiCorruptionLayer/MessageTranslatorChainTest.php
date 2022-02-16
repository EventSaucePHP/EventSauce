<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class MessageTranslatorChainTest extends TestCase
{
    /**
     * @test
     */
    public function the_chain_allows_multiple_passes_to_translate(): void
    {
        $message = new Message(new StubPublicEvent('yes'));
        $translator = new MessageTranslatorChain(
            new class implements MessageTranslator {
                public function translateMessage(Message $message): Message
                {
                    return $message->withHeader('first', 1);
                }
            },
            new class implements MessageTranslator {
                public function translateMessage(Message $message): Message
                {
                    return $message->withHeader('second', 2);
                }
            },
            new class implements MessageTranslator {
                public function translateMessage(Message $message): Message
                {
                    return $message->withHeader('third', 3);
                }
            },
        );

        $message = $translator->translateMessage($message);

        self::assertEquals(1, $message->header('first'));
        self::assertEquals(2, $message->header('second'));
        self::assertEquals(3, $message->header('third'));
    }
}
