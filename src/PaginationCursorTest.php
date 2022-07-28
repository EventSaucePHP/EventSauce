<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class PaginationCursorTest extends TestCase
{
    /**
     * @test
     */
    public function resolving_parameters_from_the_cursor(): void
    {
        $cursor = new PaginationCursor(['string' => 'name', 'int' => 1234]);

        self::assertEquals('name', $cursor->stringParam('string'));
        self::assertEquals(1234, $cursor->stringParam('int'));
    }

    /**
     * @test
     */
    public function converting_from_and_to_string(): void
    {
        $cursor = new PaginationCursor(['string' => 'name', 'int' => 1234]);

        $string = $cursor->toString();
        $fromString = PaginationCursor::fromString($string);

        self::assertEquals($cursor, $fromString);
    }

    /**
     * @test
     */
    public function fetching_an_undefined_key(): void
    {
        $cursor = new PaginationCursor(['string' => 'name', 'int' => 1234]);

        self::assertNull($cursor->stringParam('unknown'));
        self::assertNull($cursor->intParam('unknown'));
    }

    /**
     * @test
     */
    public function failing_to_create_a_cursor_from_string(): void
    {
        $this->expectException(RuntimeException::class);

        PaginationCursor::fromString('this is not a valid string');
    }
}
