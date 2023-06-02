<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Cashier\SubscriptionItem as BaseSubscriptionItem;

class SubscriptionItem extends BaseSubscriptionItem
{
    use HasUuids;

}
