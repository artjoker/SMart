<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .payment-form{
                max-width: 25%;

            }
            .StripeElement {
                background-color: white;
                padding: 8px 12px;
                border-radius: 4px;
                border: 1px solid transparent;
                box-shadow: 0 1px 3px 0 #e6ebf1;
                -webkit-transition: box-shadow 150ms ease;
                transition: box-shadow 150ms ease;
            }
            .StripeElement--focus {
                box-shadow: 0 1px 3px 0 #cfd7df;
            }
            .StripeElement--invalid {
                border-color: #fa755a;
            }
            .StripeElement--webkit-autofill {
                background-color: #fefde5 !important;
            }
        </style>

        <title>Payment</title>

    </head>
    <body>
    <div class="payment-form">
        <form action="{{ route('orders.pay') }}" id="subscribe-form" method="post">
            @csrf
            <label for="card-holder-name">Card holder name</label>
            <input id="card-holder-name" type="text">

            <!-- Stripe Elements Placeholder -->
            <div id="card-element"></div>

            <button id="card-button">
                Process Payment
            </button>
        </form>
    </div>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');

        const elements = stripe.elements();
        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        let cardElement = elements.create('card', {hidePostalCode: true,
            style: style});

        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: { name: cardHolderName.value }
                }
            );

            if (error) {
                let errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                console.log(paymentMethod)
                paymentMethodHandler(paymentMethod.id);
            }

            function paymentMethodHandler(payment_method) {
                let form = document.getElementById('subscribe-form');
                let hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', payment_method);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    </script>
    </body>
</html>
