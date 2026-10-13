<?php

namespace App\Auth;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Passport\Client;

class PassportClient extends Client
{
    protected function grantTypes(): Attribute
    {
        $default = parent::grantTypes()->get;

        return Attribute::make(get: function (?string $value, array $attributes) use ($default): array {
            if ($value !== null || ! array_key_exists('password_client', $attributes)) {
                return $default($value);
            }

            // Match Passport 12's bridge eligibility for the existing client schema.
            // Explicit grant_types on a migrated schema remain authoritative.
            return array_keys(array_filter([
                'authorization_code' => ! $this->personal_access_client && ! $this->password_client,
                'client_credentials' => $this->confidential(),
                'implicit' => true,
                'password' => $this->password_client,
                'personal_access' => $this->personal_access_client && $this->confidential(),
                'refresh_token' => true,
            ]));
        });
    }
}
