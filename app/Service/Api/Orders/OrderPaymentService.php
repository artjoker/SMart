<?php

namespace App\Service\Api\Orders;

use App\Entity\OrderStatus;
use App\Models\Client;
use App\Models\Order;
use App\Service\Api\Payment\PaymentService;
use Laravel\Cashier\Checkout;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentService
{
    private Order|null  $order;
    private Client|null $client;

    /**
     * @param \App\Service\Api\Orders\OrderService    $orderService
     * @param \App\Service\Api\Payment\PaymentService $paymentService
     */
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentService $paymentService,
    ) {
    }

    /**
     * @param string $order_id
     *
     * @return array
     */
    public function secret(
        string $order_id
    ): array {
        $this->prepearForPay($order_id);

        $payment = $this->client->pay(
            $this->paymentService->adaptSum(
                $this->order->products_sum_price,
                'stripe',
                'export'
            ),
        );

        return [
            'client_secret' => $payment->client_secret,
        ];
    }

    /**
     * @param string $order_id
     *
     * @return \Laravel\Cashier\Checkout
     */
    public function checkout(
        string $order_id
    ): Checkout {
        $this->prepearForPay($order_id);

        return $this->client->checkoutCharge(
            $this->paymentService->adaptSum(
                $this->order->products_sum_price,
                'stripe',
                'export'
            ),
            'payment for order №' . $this->order->id,
            1,
            [
                'mode'                => 'payment',
                'success_url'         => env('SUCCESS_URL'),
                'cancel_url'          => env('CANCEL_URL'),
                'payment_intent_data' => [
                    'metadata' => [
                        'order_id' => $this->order->id,
                    ],
                ],
            ]
        );
    }

    /**
     * @param string $order_id
     *
     * @return void
     */
    public function payed(string $order_id)
    {

        $order         = Order::find($order_id);
        $order->status = OrderStatus::PAYED;
        $order->save();

        //add extra logic here
    }

    /**
     * @param string $order_id
     *
     * @return void
     */
    public function refund(string $order_id)
    {

        $order         = Order::find($order_id);
        $order->status = OrderStatus::BILLED;
        $order->save();

        //add extra logic here
    }

    /**
     * @return void
     */
    private function prepearForPay(string $order_id)
    {
        $this->order = $this->orderService
            ->getById($order_id);
        $this->inBilledStatus();
        $this->client = auth()->user();
        $this->client->createOrGetStripeCustomer();
    }

    /**
     * @return void
     */
    private function inBilledStatus()
    {
        if (! $this->order || $this->order->status !== OrderStatus::BILLED) {
            abort(
                Response::HTTP_UNAUTHORIZED,
                "Order can't be payed"
            );
        }
    }

}
