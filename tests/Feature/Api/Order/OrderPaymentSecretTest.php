<?php

namespace Tests\Feature\Api\Order;

use App\Entity\OrderStatus;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderPaymentSecretTest extends TestCase
{
    private Client     $client;
    private Collection $products;

    public function setUp(): void
    {
        parent::setUp();
        $this->products = Product::factory(5)
            ->create();
        $this->client = Client::factory()
            ->createOne([
                'email' => 'test@gmail.com',
            ]);
    }

    /**
     * A basic test example.
     */
    public function test_unauthorised(): void
    {
        $response = $this->getRequestCheck(
            route('api.orders.payment.secret', [
                'order_id' => 'test',
            ]),
            401
        );
    }

    public function test_wrong_params(): void
    {
        Sanctum::actingAs(
            $this->client,
        );
        $this->getRequestCheck(
            route('api.orders.payment.secret', [
                'order_id' => 'test',
            ]),
            422
        );
    }

    public function test_wrong_order_status(): void
    {
        Sanctum::actingAs(
            $this->client,
        );
        $order = Order::factory()
            ->createOne([
                'client_id' => $this->client->id,
                'status'    => OrderStatus::CREATED,
            ]);
        $this->getRequestCheck(
            route('api.orders.payment.secret', [
                'order_id' => $order->id,
            ]),
            401
        );
    }

    public function test_client_has_stripe_id(): void
    {
        //wrong params
        Sanctum::actingAs(
            $this->client,
        );
        $order = Order::factory()
            ->createOne([
                'client_id' => $this->client->id,
                'status'    => OrderStatus::BILLED,
            ]);
        $order->products()
            ->attach($this->products);

        $this->getRequestCheck(
            route('api.orders.payment.secret', [
                'order_id' => $order->id,
            ]),
            200
        );

        $this->assertDatabaseMissing(
            'clients',
            [
                'id'        => $this->client->id,
                'stripe_id' => null,
            ]
        );
    }

    public function test_get_secret(): void
    {
        //wrong params
        Sanctum::actingAs(
            $this->client,
        );
        $order = Order::factory()
            ->createOne([
                'client_id' => $this->client->id,
                'status'    => OrderStatus::BILLED,
            ]);
        $order->products()
            ->attach($this->products);
        $response = $this->getRequestCheck(
            route('api.orders.payment.secret', [
                'order_id' => $order->id,
            ]),
            200
        );

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has(
                    'data',
                    fn (AssertableJson $json1) => $json1
                        ->has('client_secret')
                )
        );
    }

    /**
     * @param string $route
     * @param int    $code
     *
     * @return \Illuminate\Testing\TestResponse
     */
    private function getRequestCheck(string $route, int $code)
    {
        $response = $this->getJson(
            $route
        );

        $response->assertStatus($code);

        return $response;
    }
}
