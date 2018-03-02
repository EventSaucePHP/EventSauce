<?php


namespace EventSauce\EventSourcing;

/**
 * @internal
 */
interface Header
{
    const EVENT_TYPE = '__event_type';
    const EVENT_VERSION = '__event_version';
    const TIME_OF_RECORDING = '__time_of_recording';
    const AGGREGATE_ROOT_ID = '__aggregate_root_id';
    const AGGREGATE_ROOT_ID_TYPE = '__aggregate_root_id_type';
}
