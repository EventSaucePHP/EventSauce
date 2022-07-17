<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\EventConsumption\EventConsumer as PreferredEventConsumer;

/**
 * @deprecated Use EventSauce\EventSourcing\EventConsumption\EventConsumer instead
 */
abstract class EventConsumer extends PreferredEventConsumer
{
}
