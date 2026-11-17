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

        $user = $this->connection->table($this->table)
            ->where(User::PARAM_REMEMBER_TOKEN, $credentials[self::TOKEN])
            ->first();

        return $this->getGenericUser($user);
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

    protected function getGenericUser($user): ?GenericUser
    {
        return $user ? new GenericUser((array) $user) : null;
    }
}
