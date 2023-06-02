<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Service\Api\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @param \App\Service\Api\Payment\PaymentService $paymentService
     */
    public function __construct(
        private readonly PaymentService $paymentService
    ) {
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request): JsonResponse
    {
        $data = json_decode('{"id":"evt_3NE6v9HSCsH3q04F0OIk0QEc","object":"event","api_version":"2022-11-15","created":1685608308,"data":{"object":{"id":"ch_3NE6v9HSCsH3q04F0Uytv0du","object":"charge","amount":1794871,"amount_captured":1794871,"amount_refunded":0,"application":null,"application_fee":null,"application_fee_amount":null,"balance_transaction":"txn_3NE6v9HSCsH3q04F01Rpg7nB","billing_details":{"address":{"city":null,"country":"UA","line1":null,"line2":null,"postal_code":null,"state":null},"email":"cleuschke@example.com","name":"ditry","phone":null},"calculated_statement_descriptor":"Stripe","captured":true,"created":1685608307,"currency":"usd","customer":"cus_O078nbvjUJG6jh","description":null,"destination":null,"dispute":null,"disputed":false,"failure_balance_transaction":null,"failure_code":null,"failure_message":null,"fraud_details":[],"invoice":null,"livemode":false,"metadata":[],"on_behalf_of":null,"order":null,"outcome":{"network_status":"approved_by_network","reason":null,"risk_level":"normal","risk_score":29,"seller_message":"Payment complete.","type":"authorized"},"paid":true,"payment_intent":"pi_3NE6v9HSCsH3q04F0VbZa944","payment_method":"pm_1NE6v8HSCsH3q04FDfpoc0Zt","payment_method_details":{"card":{"brand":"visa","checks":{"address_line1_check":null,"address_postal_code_check":null,"cvc_check":"pass"},"country":"US","exp_month":12,"exp_year":2031,"fingerprint":"A3bZPrWSAPCJEaSf","funding":"credit","installments":null,"last4":"4242","mandate":null,"network":"visa","network_token":{"used":false},"three_d_secure":null,"wallet":null},"type":"card"},"receipt_email":null,"receipt_number":null,"receipt_url":"https:\/\/pay.stripe.com\/receipts\/payment\/CAcaFwoVYWNjdF8xTkRRWUpIU0NzSDNxMDRGKPSu4aMGMgbHJRTpxTc6LBZllzdrVNyyHG9gz4LHoLFf3ztMW2thaquU4pwZFAOQYN9OLet9GFpluM0k","refunded":false,"review":null,"shipping":null,"source":null,"source_transfer":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":1,"request":{"id":"req_KoXGqJdHS2NmaH","idempotency_key":"a67f60fa-1168-48c7-8003-146c64f9e331"},"type":"charge.succeeded"}');
        dd($data);
        //        \Log::debug(json_encode($request->all()));
        //        dd($request->all());
        return response()->json($this->paymentService->webhook());
    }

}
