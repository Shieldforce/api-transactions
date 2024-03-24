<?php

namespace Tests\Feature\ApiAuth;

use Tests\TestCase;

class Auth2RedirectTest extends TestCase
{
    /**
     * @test
     */
    public function validated_redirect_route(): void
    {
        $response = $this->get('auth/redirect');

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function redirect_to_route_callback(): void
    {
        $response = $this->get('auth/redirect');

        $this->assertTrue(
            strpos($response->baseResponse->getContent(), "oauth/authorize") !== false
        );
    }
}
