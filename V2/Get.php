<?php

namespace Miguelpelota\PaypalExpressCheckout\V2;

class CheckoutGet extends CheckoutCore
{

    private $method = 'GetExpressCheckoutDetails';
    private $token;


    public function getExpressCheckoutDetails()
    {
        $post_vars['METHOD'] = $this->method;
        $post_vars["TOKEN"]  = $this->token;

        return $this->paypalCurl($post_vars);
    }


    public function setToken($token)
    {
        $this->token = $token;
    }

}