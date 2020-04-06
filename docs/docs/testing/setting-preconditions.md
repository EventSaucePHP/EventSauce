---
permalink: /docs/testing/preconditions/
title: Setting preconditions
published_at: 2018-03-04
updated_at: 2019-12-21
---

Often you'll want to set up preconditions for tests. These are events recorded prior
to the interaction you want to test in the current scenario. This can be provided
using the `given` method. This method receives one or more events:

```php
class SigningUp extends SignUpProcessTestCase
{
    /**
     * @test
     */
    public function signing_up_works()
    {
        $this->given(
            new SignUpWasInitiated(),
            new EmailWasSpecifiedForSignUp('info@domain.tld')
        )->when(
            new FinalizeSignUpProcess()
        )->then(
            new SignUpWasFinalized()
        );
    } 
}
```

In our aggregate root this may be translated to:

```php
<?php

class SignUpProcess implements AggregateRoot
{
    use AggregateRootBehaviour;

    private $singupWasInitiated = false;
    private $emailUsed = null;

    public static function initiate(SignupId $id)
    {
        $process = new static($id);
        $process->recordThat(new SignUpWasInitiated());
    }

    public function applySignUpWasInitiated(): void
    {
        // handle
    }

    public function specifyEmailAddress(string $email): void
    {
        $this->recordThat(new EmailWasSpecifiedForSignUp($email));
    }

    public function applyEmailWasSpecifiedForSignUp(EmailWasSpecifiedForSignUp $event): void
    {
        $this->emailUsed = $event->email();
    }

    public function finalize(): void
    {
        if ( ! is_string($this->emaiUsed)) {
            throw NoEmailWasSpecifiedDuringSignup::forProcess($this->aggregateRootId);
        }

        $this->recordThat(new SignUpWasFinalized());
    }
}
```
