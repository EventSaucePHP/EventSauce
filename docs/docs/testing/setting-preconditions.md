---
permalink: /docs/testing/preconditions/
title: Setting preconditions
published_at: 2018-03-04
updated_at: 2018-03-23
---

Often you'll want to set up preconditions for tests. These are events recorded prior
to the interaction you want to test in the current scenario. This can be provided
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
