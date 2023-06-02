<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class SecretResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'client_secret' => $this['client_secret'],
        ];
    }

}
