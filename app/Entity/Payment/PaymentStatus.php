<?php

namespace App\Entity\Payment;

class PaymentStatus
{
    public const FAILED  = 0;
    public const SUCCESS = 10;
    public const REFUND  = -10;

}
