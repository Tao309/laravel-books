<?php

namespace Tests\Feature\Api;

use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

class ConfigApiTest extends TestCase
{
    use ActsAsApiClient;

    public function test_config_returns_success(): void
    {
        $this->apiGet('/api/config')->assertOk()->assertJsonPath('success', true);
    }
}
