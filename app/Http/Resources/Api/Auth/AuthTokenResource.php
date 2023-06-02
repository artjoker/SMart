<?php

namespace App\Http\Resources\Api\Auth;

use App\Http\Resources\Api\Client\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthTokenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token'  => $this->createToken('api')->plainTextToken,
            'type'   => 'Bearer',
            'client' => ClientResource::make(
                $this->resource
            ),
        ];
    }

}
