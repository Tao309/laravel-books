#!/usr/bin/env bash

# Подключение настроек
DEPLOY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Файл с настройками
ENV_FILE="$DEPLOY_DIR/deploy.env"

# Проверяем наличие файла
if [[ ! -f "$ENV_FILE" ]]; then
    echo "ERROR: deploy.env not found: $ENV_FILE"
    exit 1
fi

# Подключаем настройки и экспортируем переменные
set -a
source "$ENV_FILE"
set +a