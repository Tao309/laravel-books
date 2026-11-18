#!/usr/bin/env bash
set -Eeuo pipefail

# Подключение настроек
ENV_FILE="$DEPLOY_DIR/deploy.env"

fail() { echo "ERROR: $*" >&2; exit 1; }

# Клонируем с репозитория
[[ -n "$GIT_REPO" && "$GIT_REPO" != *"YOUR_USER"* ]] || fail "Set GIT_REPO in deploy.sh first."
[[ -n "$PHP_BIN" ]] || fail "PHP CLI not found. Set PHP_BIN to the PHP CLI path."
[[ -n "$COMPOSER_BIN" ]] || fail "Composer not found. Set COMPOSER_BIN to its executable path."
"$PHP_BIN" -r 'exit(version_compare(PHP_VERSION, "8.2.0", ">=") ? 0 : 1);' || fail "Laravel 12 requires PHP 8.2 or newer."
command -v git >/dev/null 2>&1 || fail "Git is not installed or not in PATH."

if [[ -e "$PROJECT_DIR" ]]; then
    [[ -d "$PROJECT_DIR/.git" ]] || fail "$PROJECT_DIR exists but is not a Git repository. Move it or choose another PROJECT_DIR."
    echo "Project already cloned: $PROJECT_DIR"
else
    git clone --branch "$GIT_BRANCH" --single-branch "$GIT_REPO" "$PROJECT_DIR"
fi

# Копируем env
if [[ ! -f "$LARAVEL_DIR/.env" ]]; then
    [[ -f "$DEPLOY_DIR/.env.example" ]] || fail "Custom .env.example not found in deploy directory."

    cp "$DEPLOY_DIR/.env.example" "$LARAVEL_DIR/.env"
    echo "Custom .env.example copied to Laravel project."
fi

cd "$LARAVEL_DIR"

# Установка Composer-зависимостей
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Генерация APP_KEY, если отсутствует
if ! grep -qE '^APP_KEY=base64:.+' .env; then
    "$PHP_BIN" artisan key:generate --force
fi

# Очистка кэша
"$PHP_BIN" artisan optimize:clear

# Миграции
"$PHP_BIN" artisan migrate --force --seed

# Создание storage:link
if [[ ! -L public/storage ]]; then
    "$PHP_BIN" artisan storage:link
fi

# Копирование .htaccess в public
if [[ -f "$DEPLOY_DIR/public/.htaccess" ]]; then
    cp "$DEPLOY_DIR/public/.htaccess" "$PUBLIC_DIR/.htaccess"
    echo ".htaccess copied to public/"
else
    echo "WARNING: .htaccess not found in project root."
fi


# Создание символической ссылки public_html -> public
if [[ -L "$PUBLIC_HTML" ]]; then
    # Ссылка уже существует
    echo "Symlink public_html already exists."
elif [[ -e "$PUBLIC_HTML" ]]; then
    # Существует обычная директория или файл
    echo "WARNING: $PUBLIC_HTML already exists and is not a symlink."
    echo "Please check it manually. Symlink was not created."
else
    ln -s "$PUBLIC_DIR" "$PUBLIC_HTML"
    echo "Symlink created: $PUBLIC_HTML -> $PUBLIC_DIR"
fi

# Оптимизация Laravel
"$PHP_BIN" artisan optimize

echo
echo "Laravel first deployment completed."
echo "Configure the website document root to: $PUBLIC_DIR"
