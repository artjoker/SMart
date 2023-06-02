<?php

namespace Tests\Feature\Api\Login;

use App\Models\Client;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()
            ->createOne([
                'email' => 'test@gmail.com',
            ]);
    }

    public function test_wrong_request_method(): void
    {
        $response = $this->getJson(
            route('api.auth.login'),
        );

        $response->assertStatus(405);

    }

    public function test_wrong_login(): void
    {
        $this->postRequestCheck(
            route('api.auth.login'),
            [
                'email'    => 'test1@gmail.com',
                'password' => 'password',
            ],
            401
        );
    }

    public function test_wrong_password(): void
    {
        $this->postRequestCheck(
            route('api.auth.login'),
            [
                'email'    => 'test@gmail.com',
                'password' => 'passw1ord',
            ],
            401
        );
    }

    public function test_login_success(): void
    {
        $response = $this->postRequestCheck(
            route('api.auth.login'),
            [
                'email'    => 'test@gmail.com',
                'password' => 'password',
            ],
            200
        );

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has(
                    'data',
                    fn (AssertableJson $json1) => $json1
                        ->has('token')
                        ->has('type')
                        ->has(
                            'client',
                            fn (AssertableJson $json2) => $json2
                                ->has('id')
                                ->has('first_name')
                                ->has('last_name')
                                ->has('email')
                                ->etc()
                        )
                        ->etc()
                )
                ->etc()
        );
    }

    /**
     * @param string $route
     * @param array  $data
     * @param int    $code
     *
     * @return \Illuminate\Testing\TestResponse
     */
    private function postRequestCheck(string $route, array $data, int $code)
    {
        $response = $this->postJson(
            $route,
            $data
        );

        $response->assertStatus($code);

        return $response;
    }
}
