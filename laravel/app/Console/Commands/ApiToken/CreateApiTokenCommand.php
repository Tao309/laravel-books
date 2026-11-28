<?php

namespace App\Console\Commands\ApiToken;

use App\Enums\MessengerPlatform;
use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;

class CreateApiTokenCommand extends Command
{
    protected $signature = 'app:token-create
                            {username : name пользователя}
                            {platform : telegram, max или vk}
                            {--name= : Название токена}';

    protected $description = 'Создать API-токен пользователя';

    public function handle(ApiTokenService $service): int
    {
        $platform = MessengerPlatform::tryFrom(
            $this->argument('platform')
        );

        if (! $platform) {
            $this->error('Неизвестная платформа. Доступны: telegram, max, vk.');

            return self::FAILURE;
        }

        $userName = $this->argument('username');
        $user = User::query()
            ->where('name', $userName)
            ->first();

        if (! $user) {
            $this->warn("Пользователь '{$userName}' не найден.");

            if (! confirm('Создать нового пользователя?')) {
                $this->info('Создание API-токена отменено.');

                return self::FAILURE;
            }

            $password = Str::password(16);

            $user = User::query()->create([
                'name' => $userName,
                'email' => $userName . '@localhost.ru',
                'password' => Hash::make($password),
            ]);

            $this->info("Пользователь '{$userName}' создан.");
            $this->line("ID пользователя {$user->id}");
            $this->line("Пароль пользователя: {$password}");
        }

        $result = $service->create(
            $user,
            $platform,
            $this->option('name')
        );

        $this->newLine();
        $this->info('API-токен успешно создан.');
        $this->line('ApiToken ID: ' . $result['model']->id);
        $this->line('ApiToken Name: ' . ($result['model']->name ?? '-'));
        $this->line('User ID: ' . $user->id);

        $this->newLine();
        $this->warn('Сохраните токен. Повторно он не отображается.');
        $this->line($result['token']);
        $this->newLine();

        return self::SUCCESS;
    }
}
