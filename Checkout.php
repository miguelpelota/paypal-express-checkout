<?php

namespace Miguelpelota\PaypalExpressCheckout;

class Checkout
{

    /**
     * [$_config configuration settings for PayPal merchant]
     * @var array
     */
    private static $_config = array(
        'USER'                              => 'miguelpelota1-facilitator_api1.yahoo.com',
        'PWD'                               => '1398470149',
        'SIGNATURE'                         => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A2Cs0-R7sHqfJkaQPXEad2XvfW8a',
        'HDRIMG'                            => 'http://placekitten.com/g/150/150',
        'VERSION'                           => '119',
        'PAYMENTREQUEST_0_PAYMENTACTION'    => 'SALE',
        'PAYMENTREQUEST_0_CURRENCYCODE'     => 'USD'
    );


    /**
     * @param  string  paypal nvp's
     * @return array   paypal api response data
     */
    private static function _paypalCurl($post_vars)
    {
        $post_vars = array_merge($post_vars, self::$_config);

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


    /**
     *  @param  array   items, tax rate, shipping, return url, cancel url
     *  @return array   paypal token, ack, timestamp, etc.
     */
    public static function setExpressCheckout($data)
    {
        self::$_config['METHOD'] = 'SetExpressCheckout';

        $subtotal = 0;

        $i = 0;

        foreach ( $data['items'] as $item )
        {
            $post_vars['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
            $post_vars['L_PAYMENTREQUEST_0_AMT'  . $i] = $item['unit_price'];
            $post_vars['L_PAYMENTREQUEST_0_QTY'  . $i] = $item['qty'];
            $post_vars['L_PAYMENTREQUEST_0_DESC' . $i] = $item['description'];

            $subtotal += $item['unit_price'] * $item['qty'];

            $i++;
        }

        $post_vars['PAYMENTREQUEST_0_ITEMAMT']       = round($subtotal, 2);
        $post_vars['PAYMENTREQUEST_0_TAXAMT']        = round($data['tax_rate'] * $post_vars['PAYMENTREQUEST_0_ITEMAMT'], 2);
        $post_vars['PAYMENTREQUEST_0_SHIPPINGAMT']   = $data['shipping_amount'];
        $post_vars['PAYMENTREQUEST_0_AMT']           = round($post_vars['PAYMENTREQUEST_0_ITEMAMT'] + $post_vars['PAYMENTREQUEST_0_TAXAMT'] + $post_vars['PAYMENTREQUEST_0_SHIPPINGAMT'], 2);

        $post_vars['RETURNURL'] = $data['return_url'];
        $post_vars['CANCELURL'] = $data['cancel_url'];

        $post_vars['ALLOWNOTE'] = false;

        if ( isset($data['instant_update']) )
        {
            $post_vars['CALLBACK'] = $data['callback_url'];
            $post_vars['CALLBACKTIMEOUT'] = 6;

            $post_vars['L_SHIPPINGOPTIONNAME0'] = 'Flat Rate Shipping';
            $post_vars['L_SHIPPINGOPTIONAMOUNT0'] = $data['shipping_amount'];
            $post_vars['L_SHIPPINGOPTIONISDEFAULT0'] = true;

            $post_vars['MAXAMT'] = $post_vars['PAYMENTREQUEST_0_AMT'] + 100;

            $post_vars['PAYMENTREQUEST_0_INSURANCEOPTIONOFFERED'] = false;
        }

        return self::_paypalCurl($post_vars);
    }


    /**
     *  @param  string  token
     *  @return array   collection of items, shipping info, customer info, etc.
     */
    public static function getExpressCheckoutDetails($token)
    {
        self::$_config['METHOD'] = 'GetExpressCheckoutDetails';

        $post_vars["TOKEN"] = $token;

        return self::_paypalCurl($post_vars);
    }


    /**
     *  @param  array   payerid, token, amt
     *  @return array   ack, timestamp, fee, etc.
     */
    public static function doExpressCheckoutPayment( $post_vars )
    {
        self::$_config['METHOD'] = 'DoExpressCheckoutPayment';

        return self::_paypalCurl($post_vars);
    }

}