<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\UuidMessageDecorator;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\FieldsInterface;
use Ramsey\Uuid\Uuid;
use function assert;

class UuidV4GeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function generating_uuid_v4_uuids(): void
    {
        $generator = new UuidV4Generator();

        $id = $generator->generate();
        $uuid = Uuid::fromString($id);

        $fields = $uuid->getFields();
        assert($fields instanceof FieldsInterface);
        self::assertEquals(4, $fields->getVersion());
    }
}
