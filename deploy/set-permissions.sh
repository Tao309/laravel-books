#!/usr/bin/env bash

set -Eeuo pipefail

DEPLOY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

chmod +x "$DEPLOY_DIR/deploy.sh"
chmod +x "$DEPLOY_DIR/update.sh"
chmod +x "$DEPLOY_DIR/db_refresh.sh"

chmod 600 "$DEPLOY_DIR/deploy.env"
chmod 600 "$DEPLOY_DIR/.env.example"