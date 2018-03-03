<?php

namespace EventSauce\EventSourcing;

class DefaultHeadersDecorator implements MessageDecorator
{
    /**
     * @var ClassNameInflector
     */
    private $inflector;

    public function __construct(ClassNameInflector $inflector = null)
    {
        $this->inflector = $inflector ?: new DotSeparatedSnakeCaseInflector();
    }

    public function decorate(Message $message): Message
    {
        $event = $message->event();
        $id = $message->header(Header::AGGREGATE_ROOT_ID);
        $headers = [
            Header::EVENT_TYPE        => $this->inflector->instanceToType($event),
            Header::TIME_OF_RECORDING => $event->timeOfRecording()->toString(),
        ];

        if ($id instanceof AggregateRootId) {
            $headers[Header::AGGREGATE_ROOT_ID] = $id->toString();
            $headers[Header::AGGREGATE_ROOT_ID_TYPE] = $this->inflector->instanceToType($id);
        }

        return $message->withHeaders($headers);
    }
}