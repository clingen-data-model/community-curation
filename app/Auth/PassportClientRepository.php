<?php

namespace App\Auth;

use Laravel\Passport\Bridge\ClientRepository;

class PassportClientRepository extends ClientRepository
{
    public function validateClient(string $clientIdentifier, ?string $clientSecret, ?string $grantType): bool
    {
        $client = $this->clients->findActive($clientIdentifier);

        if (! $client || $clientSecret === null || $clientSecret === '' || ! $client->secret) {
            return false;
        }

        // Passport 13 hashes new secrets. Accept existing plaintext secrets without
        // rewriting them, while never treating a stored password hash as plaintext.
        if (password_get_info($client->secret)['algo'] !== null) {
            return $this->hasher->check($clientSecret, $client->secret);
        }

        return hash_equals($client->secret, $clientSecret);
    }
}
