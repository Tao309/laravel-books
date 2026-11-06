<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\ConnectionInterface;

class CustomUserProvider implements UserProvider
{
    public const string TOKEN = 'token';

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly string $table,
    ) {}

    public function retrieveById($identifier) {}

    public function retrieveByToken($identifier, $token)
    {
        //
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        //
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials[self::TOKEN])) {
            return null;
        }

        $user = $this->connection->table($this->table)
            ->where(User::PARAM_REMEMBER_TOKEN, $credentials[self::TOKEN])
            ->first();

        return $this->getGenericUser($user);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        if (is_null($credentials[self::TOKEN])) {
            return false;
        }

        return true;
    }

    public function rehashPasswordIfRequired(
        Authenticatable $user,
        #[\SensitiveParameter] array $credentials,
        bool $force = false)
    {
        //
    }

    protected function getGenericUser($user): ?GenericUser
    {
        return (! is_null($user)) ? new GenericUser((array) $user) : null;
    }
}
