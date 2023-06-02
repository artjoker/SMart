<?php

namespace App\Service\Api\Dto\Payment;

readonly class PaymentDto
{
    /**
     * @param string $customer
     * @param string $order_id
     * @param string $stripe_id
     * @param float  $subtotal
     * @param float  $tax
     * @param float  $total
     * @param int    $status
     */
    public function __construct(
        private string $customer,
        private string $order_id,
        private string $stripe_id,
        private float $subtotal,
        private float $tax,
        private float $total,
        private int $status,
    ) {
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @return string
     */
    public function getStripeId(): string
    {
        return $this->stripe_id;
    }

    /**
     * @return float
     */
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

}
