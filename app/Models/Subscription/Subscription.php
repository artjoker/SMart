<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Cashier\Subscription as BaseSubscription;

class Subscription extends BaseSubscription
{
    use HasUuids;

}
