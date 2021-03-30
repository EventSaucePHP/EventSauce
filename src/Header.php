<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface Header
{
    public const EVENT_ID = '__event_id';
    public const EVENT_TYPE = '__event_type';
    public const TIME_OF_RECORDING = '__time_of_recording';
    public const AGGREGATE_ROOT_ID = '__aggregate_root_id';
    public const AGGREGATE_ROOT_ID_TYPE = '__aggregate_root_id_type';
    public const AGGREGATE_ROOT_VERSION = '__aggregate_root_version';
}
