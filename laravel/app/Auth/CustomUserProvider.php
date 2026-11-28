<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Database\ConnectionInterface;

class CustomUserProvider implements UserProvider
{
    public const string TOKEN = 'token';

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly HasherContract $hasher,
        private readonly string $table,
    ) {}

    /**
     * @codeCoverageIgnore
     */
    public function retrieveById($identifier) {}

    /**
     * @codeCoverageIgnore
     */
    public function retrieveByToken($identifier, $token) {}

    /**
     * @codeCoverageIgnore
     */
    public function updateRememberToken(Authenticatable $user, $token) {}

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials[self::TOKEN])) {
            return null;
        }

        return User::query()
            ->with('roles')
            ->where(
                User::PARAM_TOKEN_HASH,
                hash('sha256', $credentials[self::TOKEN])
            )
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return ! empty($credentials[self::TOKEN]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function rehashPasswordIfRequired(
        Authenticatable $user,
        #[\SensitiveParameter] array $credentials,
        bool $force = false) {}
}
