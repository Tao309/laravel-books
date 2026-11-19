#!/usr/bin/env bash
set -Eeuo pipefail

# Подключение настроек
ENV_FILE="$DEPLOY_DIR/deploy.env"

cd "$LARAVEL_DIR"

# Пересоздаём БД
"$PHP_BIN" artisan migrate:fresh --seed

# Импорт данных
"$PHP_BIN" artisan app:import:run-all --fake=0 --just-check-parsing=0

echo "Laravel DB refresh completed."
