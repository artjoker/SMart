<?php

namespace App\Service\Api\Payment;

use App\Entity\Payment\PaymentStatus;
use App\Entity\Payment\StripeWebhookEvents;
use App\Models\Client;
use App\Models\Payment;
use App\Service\Api\Dto\Payment\PaymentDto;

class PaymentService
{
    private float|int $amount     = 0;
    private int       $multiplier = 1;

    public function createPayment(PaymentDto $paymentDto)
    {
        $client = Client::query()
            ->where('stripe_id', $paymentDto->getCustomer())
            ->first();

        $payment            = Payment::firstOrNew(['stripe_id' => $paymentDto->getStripeId()]);
        $payment->client_id = is_null($client) ? null : $client->id;
        $payment->order_id  = $paymentDto->getOrderId();
        $payment->subtotal  = $paymentDto->getSubtotal();
        $payment->tax       = $paymentDto->getTax();
        $payment->total     = $paymentDto->getTotal();
        $payment->status    = $paymentDto->getStatus();
        $payment->save();
    }

    public function adaptSum(
        float|int $amount,
        string $system,
        string $type
    ): int|float {
        $this->setAmount($amount);
        $this->setSystem($system);

        return $this->adaptAmount($type);
    }

    public function setSystem(string $system)
    {
        switch ($system) {
            case 'stripe':
                $this->multiplier = 100;

                break;
            default:
                $this->multiplier = 1;
        }
    }

    public function setAmount(float|int $amount)
    {
        $this->amount = $amount;
    }

    public function adaptAmount(string $type): float|int
    {
        switch ($type) {
            case 'import':
                return $this->amount / $this->multiplier;
            case 'export':
                return $this->amount * $this->multiplier;
        }
    }

    /**
     * @param string $type
     *
     * @return int
     */
    public function getPaymentStatusStripe(string $type): int
    {
        return match ($type) {
            StripeWebhookEvents::CHARGE_SUCCEEDED => PaymentStatus::SUCCESS,
            StripeWebhookEvents::CHARGE_REFUNDED  => PaymentStatus::REFUND,
            default                               => PaymentStatus::FAILED,
        };
    }
}
