<?php

namespace App\Services;

use App\Entity\Cart;

/**
 * @param Cart $cart
 * @return \Stripe\PaymentIntent
 * @throws \Stripe\Exception\ApiErrorException
 */
class StripeService
{
    private $privateKey;

    public function __construct()
    {
        $this->privateKey = 'sk_test_51JWgqrAHXukslQqCvnHyP1p3tUwpybrmZlxor6yrPOCjbD4OJcXPZnZcVR6j3edpKtKaULLVZAlqKj1ruoOQPGnL00Vd8bjbcy';
    }

    public function paymentIntent(Cart $cart)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create()([
            'amount' => $cart->getPrice() * $cart->getQuantity()* 100,
            'currency' => 'eur',
            'payment_method_types' => ['card']
        ]);
    }

    public function paiement($amount, $currency, $description, array $stripeParameter)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        $payment_intent = null;
        
        if(isset($stripeParameter['StripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['StripeIntentId']);
    }

        if($stripeParameter['StripeIntentId'] === 'succeeded'){
            //TODO
        }   else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }

    /**
    * @param array $stripeParameter
    * @param Cart $cart 
    * @return \Stripe\PaymentIntent|null
    */
    public function stripe(array $stripeParameter, Cart $cart)
    {
        return $this->paiement(
            $cart->getQuantity() * $cart->getQuantity() *100,
            'eur',
            $cart->getAlbumName(),
            $stripeParameter
        );
    }
}