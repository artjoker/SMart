<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Orders\OrderPaymentRequest;
use App\Http\Resources\Api\Order\CheckoutResource;
use App\Http\Resources\Api\Order\SecretResource;
use App\Service\Api\Orders\OrderPaymentService;

class OrderPaymentController extends Controller
{
    /**
     * @param \App\Service\Api\Orders\OrderPaymentService $orderPaymentService
     */
    public function __construct(
        private readonly OrderPaymentService $orderPaymentService
    ) {
    }

    /**
     * @param \App\Http\Requests\Api\Orders\OrderPaymentRequest $request
     *
     * @return \App\Http\Resources\Api\Order\SecretResource
     */
    public function secret(OrderPaymentRequest $request): SecretResource
    {
        return new SecretResource($this->orderPaymentService->secret($request->order_id));
    }

    /**
     * @param \App\Http\Requests\Api\Orders\OrderPaymentRequest $request
     *
     * @return \App\Http\Resources\Api\Order\CheckoutResource
     */
    public function checkout(OrderPaymentRequest $request): CheckoutResource
    {
        return new CheckoutResource($this->orderPaymentService->checkout($request->order_id));
    }

}
