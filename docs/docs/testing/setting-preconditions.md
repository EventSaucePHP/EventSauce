---
layout: default
permalink: /docs/testing/preconditions/
title: Setting preconditions
---

# Setting preconditions

Often you'll want to setup preconditions for tests. These are events recorded prior
to the interaction you want to test in the current scenario. This can be done
using the `given` method. This method receives one or more events:

```php
class SignUpRespectsBlackListTest extends SignUpProcessTestCase
{
    /**
     * @test
     */
    public function blacklist_is_respected()
    {
        $this->given(
            new SignUpWasInitiated($this->pointInTime()),
            new EmailWasSpecifiedForSignUp($this->pointInTime(), 'info@domain.tld')
        )->when(
            new FinalizeSignUpProcess($this->pointInTime())
        )->then(
            new SignUpWasFinalized($this->pointInTime())
        );
    } 
}
``` 

