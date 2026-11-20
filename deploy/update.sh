#!/usr/bin/env bash
set -Eeuo pipefail

DEPLOY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Подключаем загрузчик настроек
source "$DEPLOY_DIR/load-env.sh"

cd "$PROJECT_DIR"

fail() { echo "ERROR: $*" >&2; exit 1; }

# Обновляем с репозитория
[[ -d .git ]] || fail "Git repository not found."
if [[ -n "$(git status --porcelain)" ]]; then
    fail "Git working tree is not clean. Commit/stash changes and ensure .env is in .gitignore."
fi

git fetch "$GIT_REMOTE" "$GIT_BRANCH"
git pull --ff-only "$GIT_REMOTE" "$GIT_BRANCH"

# Копируем env
if [[ ! -f "$LARAVEL_DIR/.env" ]]; then
    [[ -f "$DEPLOY_DIR/.env.example" ]] || fail "Custom .env.example not found in deploy directory."

    cp "$DEPLOY_DIR/.env.example" "$LARAVEL_DIR/.env"
    echo "Custom .env.example copied to Laravel project."
fi

# Установка Composer-зависимостей
cd "$LARAVEL_DIR"
[[ -f artisan && -f composer.json && -f composer.lock ]] || fail "Laravel project files or composer.lock not found."
[[ -f .env ]] || fail ".env not found. Configure production environment first."
[[ -n "$PHP_BIN" ]] || fail "PHP CLI not found. Set PHP_BIN if needed."
[[ -n "$COMPOSER_BIN" ]] || fail "Composer not found. Set COMPOSER_BIN if needed."
"$PHP_BIN" -r 'exit(version_compare(PHP_VERSION, "8.2.0", ">=") ? 0 : 1);' || fail "Laravel 12 requires PHP 8.2 or newer."
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Очистка кэша
"$PHP_BIN" artisan optimize:clear

# Миграции
"$PHP_BIN" artisan migrate --force

# Создание storage:link
if [[ ! -L public/storage ]]; then
    "$PHP_BIN" artisan storage:link
fi

# Очистка старых кэшей
"$PHP_BIN" artisan optimize:clear

# Создание production-кэшей
"$PHP_BIN" artisan optimize

# Установка прав
source "$DEPLOY_DIR/set-permissions.sh"

echo "Laravel update completed."
