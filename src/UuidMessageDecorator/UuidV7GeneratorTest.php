<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\UuidMessageDecorator;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\FieldsInterface;
use Ramsey\Uuid\Uuid;
use function assert;
use function function_exists;
use function method_exists;

class UuidV7GeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function generating_uuid_v7_uuids(): void
    {
        if (!method_exists(Uuid::class, 'uuid7')) {
            $this->markTestSkipped('Ramsey\Uuid\Uuid::uuid7 is not available.');
        }

        $generator = new UuidV7Generator();

        $id = $generator->generate();
        $uuid = Uuid::fromString($id);

        $fields = $uuid->getFields();
        assert($fields instanceof FieldsInterface);
        self::assertEquals(7, $fields->getVersion());
    }
}
