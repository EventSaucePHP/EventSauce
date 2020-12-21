<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Time;

use DateTimeImmutable;
use DateTimeZone;

interface Clock
{
    public function currentTime(): DateTimeImmutable;

    public function timeZone(): DateTimeZone;
}
