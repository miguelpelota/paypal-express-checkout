<?php

namespace Miguelpelota\PaypalExpressCheckout\V2;

abstract class CheckoutCore
{

    private $config = array(
        // 'USER'                        => 'XXXXXX',
        // 'PWD'                         => 'XXXXXX',
        // 'SIGNATURE'                   => 'XXXXXX',
        'USER'                           => 'miguelpelota1-facilitator_api1.yahoo.com',
        'PWD'                            => '1398470149',
        'SIGNATURE'                      => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A2Cs0-R7sHqfJkaQPXEad2XvfW8a',
        'HDRIMG'                         => 'http://placekitten.com/g/150/150',
        'VERSION'                        => '119',
        'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
        'PAYMENTREQUEST_0_CURRENCYCODE'  => 'USD'
    );


    public function paypalCurl($post_vars)
    {
        $post_vars = array_merge($post_vars, $this->config);

        $post_vars = http_build_query($post_vars);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $data = curl_exec($ch);

        parse_str($data, $data);

        return $data;
    }

}