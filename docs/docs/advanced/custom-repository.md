---
permalink: /docs/advanced/custom-repository/
title: Custom Message Repository
published_at: 2018-03-07
updated_at: 2019-12-21
---

You can create a custom implementation of the message repository if needed. Your
class must implement the `MessageRepository` interface:

```php
<?php

namespace EventSauce\EventSourcing;

use Generator;

interface MessageRepository
{
    public function persist(Message ... $messages);
    public function retrieveAll(AggregateRootId $id): Generator;
}
```

It's recommended to leverage the `MessageSerializer` capabilities. The following
is an example of a filesystem-based message repository:

```php
<?php

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;

class FilesystemMessageRepository implements MessageRepository
{
    private $serializer;
    
    public function __construct(MessageSerializer $serializer = null)
    {
        $this->serializer = $serializer ?: new ConstructingMessageSerializer();
    }
    
    public function persist(Message ... $messages)
    {
        foreach ($messages as $message) {
            $aggregateRootId = $message->header(Header::AGGREGATE_ROOT_ID);
            $version = $message->header(Header::AGGREGATE_ROOT_VERSION);
            
            if ( ! is_dir(__DIR__.'/'.$aggregateRootId)) {
                mkdir(__DIR__.'/'.$aggregateRootId);
            }

            $payload = $this->serializer->serializeMessage($message);
            file_put_contents(__DIR__."/{$aggregateRootId}/{$version}.json", json_encode($payload, JSON_PRETTY_PRINT));
        }
    }
    
    public function retrieveAll(AggregateRootId $id): Generator
    {
        $directory = __DIR__.'/'.$id->toString();
        
        if ( ! is_dir($directory)) {
            return 0;
        }
        
        foreach (array_diff(scandir($directory), array('..', '.')) as $file) {
            $message = $this->serializer->unserializePayload(
                json_decode(
                    file_get_contents($directory.'/'.$file),
                    true
                )
            );

            yield $message;
        }

        return isset($message) ? $message->header(Header::AGGREGATE_ROOT_VERSION) : 0;
    }
    public function retrieveAllAfterVersion(AggregateRootId $id, int $version): Generator
    {
        $directory = __DIR__.'/'.$id->toString();
                
        if ( ! is_dir($directory)) {
            return 0;
        }
        
        foreach (array_diff(scandir($directory), array('..', '.')) as $file) {
            if ($version >= (int) $file) continue;

            $message = $this->serializer->unserializePayload(
                json_decode(
                    file_get_contents($directory.'/'.$file),
                    true
                )
            );

            yield $message;
        }

        return isset($message) ? $message->header(Header::AGGREGATE_ROOT_VERSION) : 0;
    }

}
```
