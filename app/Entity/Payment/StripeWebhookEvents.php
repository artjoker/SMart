<?php

namespace App\Entity\Payment;

class StripeWebhookEvents
{
    public const CHARGE_CAPTURED  = 'charge.captured';
    public const CHARGE_EXPIRED   = 'charge.expired';
    public const CHARGE_FAILED    = 'charge.failed';
    public const CHARGE_PENDING   = 'charge.pending';
    public const CHARGE_REFUNDED  = 'charge.refunded';
    public const CHARGE_SUCCEEDED = 'charge.succeeded';

}
