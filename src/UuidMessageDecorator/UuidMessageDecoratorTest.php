<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\UuidMessageDecorator;

use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use PHPStan\Testing\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidMessageDecoratorTest extends TestCase
{
    /**
     * @test
     */
    public function decorating_messages(): void
    {
        $decorator = new UuidMessageDecorator();
        $message = new Message(new EventStub('value'));

        $decoratedMessage = $decorator->decorate($message);
        $eventId = $decoratedMessage->header(Header::EVENT_ID);

        $uuid = Uuid::fromString($eventId);
        self::assertInstanceOf(UuidInterface::class, $uuid);
    }
}
