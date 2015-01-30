<?php

namespace Miguelpelota\PaypalExpressCheckout\V2;

class CheckoutDo extends CheckoutCore
{

    private $method = 'DoExpressCheckoutPayment';
    private $token;
    private $payer_id;
    private $grand_total;

    public function doExpressCheckoutPayment()
    {
        $post_vars['METHOD'] = $this->method;

        $post_vars['TOKEN']                = $this->token;
        $post_vars['PAYERID']              = $this->payer_id;
        $post_vars['PAYMENTREQUEST_0_AMT'] = $this->grand_total;

        return $this->paypalCurl($post_vars);
    }


    public function setToken($token)
    {
        $this->token = $token;
    }


    public function setPayerId($payer_id)
    {
        $this->payer_id = $payer_id;
    }


    public function setGrandTotal($grand_total)
    {
        $this->grand_total = $grand_total;
    }

}