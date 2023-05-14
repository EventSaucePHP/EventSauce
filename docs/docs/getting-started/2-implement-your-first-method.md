---
permalink: /docs/getting-started/implement-your-first-method/
title: Implement your first method
---

In event sourcing, actions performed on entities (aggregate roots) record events
to capture the outcome of said action. The event captures the intent of the moment
in time, along with any relevant context. In EventSauce events are represented as
simple PHP classes/objects.

As an example, let's implement an account creation flow. The flow is a multistep process
that results in an account being created. Our flow is short and simple, but you can imagine
more elaborate flows being modelled in a similar manner. In this example, our flow consists
of: 

1. starting the account creation process
2. specifying login credentials
3. accepting the terms and finalizing the process

The code below is the body of our `AccountCreation` class.
```php
private string|null $email = null;
private string|null $passwordHash = null;
private bool $finalized = false;
private bool $credentialsProvided = false;
    
// start the creation process
public static function startAccountCreation(ApplicationId $id): AccountCreation
{
    $process = new AccountCreation($id);
    $process->recordThat(AccountCreationHasStarted());    
    
    return $process;
}

public function useLoginCredentials(
    string $email,
    string $password,
    PasswordEncoder $passwordEncoder,
): void {
    $this->guardAgainstChangingFinalizedAccount();

    if (strlen($password) < 8) {
        throw InvalidPasswordProvided::becauseItIsTooShort($password);
    }
    
    $passwordHash = $passwordEncoder->encodePassword($password);
    
    $this->recordThat(
        new LoginCredentialsWereProvided($email, $passwordHash)
    );
}

protected function applyLoginCredentialsWereProvided(
    LoginCredentialsWereProvided $event
) {
    $this->credentialProvided = true;
    $this->email = $event->email();
    $this->passwordHash = $event->passwordHash();
}

public function finalizeAccount(
    bool $acceptTerms,
    AccountRepository $accounts,
): void {
    $this->guardAgainstChangingFinalizedAccount();

    if ( ! $acceptTerms) {
        throw UnableToFinalizeAccount::termsWereNotAccepted();
    }
    
    if ( ! $this->credentialsProvided) {
        throw UnableToFinalizeAccount::credentialsWereNotProvided();
    }
}

private function guardAgainstChangingFinalizedAccount(): void
{
    if ($this->finalized) {
        throw new UnableToChangeFinalizedAccount();
    }
}
```
