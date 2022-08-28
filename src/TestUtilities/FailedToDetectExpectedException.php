<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

use Exception;
use LogicException;

use function get_class;

/**
 * @internal
 */
final class FailedToDetectExpectedException extends LogicException
{
    public static function expectedException(Exception $expectedException): FailedToDetectExpectedException
    {
        return new FailedToDetectExpectedException(
            'Failed to detect expected exception, was expecting exception of type ' . get_class($expectedException),
            0,
            $expectedException
        );
    }
}
