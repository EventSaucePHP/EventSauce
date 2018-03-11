---
permalink: /docs/testing/handling-exceptions/
title: Handling Exceptions
---

# Handling exceptions

Handling exceptions is an important part of software modeling. EventSauce
makes it easy to test failures. If our `AggregateRoot` guard invariants
we can create tests for that to ensure our business rule is respected.

For example, if there's a business rule that nobody on a blacklist can
subscribe, we can create a test for this:

```php

<?php

namespace AmceCompany\SigningUp;

class SignUpRespectsBlackListTest extends SignUpProcessTestCase
{
    /**
     * @test
     */
    public function blacklist_is_respected()
    {
        $this->given(
            new SignUpWasInitiated($this->pointInTime())
        )->when(SpecifyEmailForSignUp(
            $this->pointInTime(),
            $email = 'blacklisted@e-mail.com'
        ))->expectToFail(
            SorryEmailAddressIsBlackListed::forEmail($email)
        );
    } 
}
``` 

## Recording events on failure

If you want to react to failures (exceptional cases) make sure you
persist the aggregate in a `finally` class in your handler:

```yaml
protected function handle($command)
{
    $process = $this->repository->retrieve($command->processId());
    
    try {
        if ($command instanceof InitiateSignUpProcess) {
            $process->initiate($this->clock());           
        }
    } finally {
        $this->repository->persist($process);
    }
}
```

The `finally` class will be triggered even though a exception is thrown. In
this case the events recorded prior to the exception are still recorded but
your exception still bubbles up so you can handle it transparently.

