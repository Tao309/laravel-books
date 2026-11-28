<?php

declare(strict_types=1);

namespace App\Enums;

enum MessengerPlatform: string
{
    case Telegram = 'telegram';
    case Max = 'max';
    case Vk = 'vk';
}
