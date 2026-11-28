<?php

namespace App\Enums;

use App\Http\Middleware\CheckRole;

enum RoleCode: string
{
    case API = 'api';
    case BOT = 'bot';
    case ADMIN = 'admin';
    case USER = 'user';
    case MODERATOR = 'moderator';
    case MANAGER = 'manager';
    case CONTENT_MANAGER = 'content_manager';
    case CUSTOMER = 'customer';

    public function getMiddlewarePath(): string
    {
        return CheckRole::ROLE . ':' . $this->value;
    }
}
