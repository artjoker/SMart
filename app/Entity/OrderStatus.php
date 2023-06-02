<?php

namespace App\Entity;

class OrderStatus
{
    //base statuses
    public const CREATED  = 10;
    public const APPROVED = 20;
    public const BILLED   = 30;
    public const PAYED    = 40;

    //unsuccess statuses
    public const REJECTED = -10;

}
