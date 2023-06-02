<?php

namespace App\Listeners;

namespace App\Listeners;

use App\Entity\Payment\StripeWebhookEvents;
use App\Service\Api\Dto\Payment\PaymentDto;
use App\Service\Api\Orders\OrderPaymentService;
use App\Service\Api\Payment\PaymentService;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        public readonly PaymentService $paymentService,
        public readonly OrderPaymentService $orderPaymentService,
    ) {
    }

    /**
     * Handle received Stripe webhooks.
     */
    public function handle(WebhookReceived $event): void
    {
        Log::debug('ans', $event->payload);
        //save payment
        $this->paymentService->createPayment(new PaymentDto(
            customer: $event->payload['data']['object']['customer'],
            order_id: $event->payload['data']['object']['metadata']['order_id'],
            stripe_id: $event->payload['data']['object']['id'],
            subtotal: $this->paymentService->adaptSum(
                $event->payload['data']['object']['amount'],
                'stripe',
                'import'
            ),
            tax: 0,
            total: $this->paymentService->adaptSum(
                $event->payload['data']['object']['amount'],
                'stripe',
                'import'
            ),
            status: $this->paymentService->getPaymentStatusStripe($event->payload['type'])
        ));

        //action
        switch ($event->payload['type']) {
            case StripeWebhookEvents::CHARGE_SUCCEEDED:
                $this->orderPaymentService->payed($event->payload['data']['object']['metadata']['order_id']);

                break;
            case StripeWebhookEvents::CHARGE_REFUNDED:
                $this->orderPaymentService->refund($event->payload['data']['object']['metadata']['order_id']);

                break;
        }
    }
}
