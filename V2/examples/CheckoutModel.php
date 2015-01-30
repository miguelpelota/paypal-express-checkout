<?php

require_once '../Core.php';
require_once '../Set.php';
require_once '../Get.php';
require_once '../Do.php';

use \Miguelpelota\PaypalExpressCheckout\V2\CheckoutCore;
use \Miguelpelota\PaypalExpressCheckout\V2\CheckoutSet;
use \Miguelpelota\PaypalExpressCheckout\V2\CheckoutGet;
use \Miguelpelota\PaypalExpressCheckout\V2\CheckoutDo;

$_checkout = new CheckoutModel();

// $_checkout->setExpressCheckout();
// header('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-4LH63646GC509300W');
// http://localhost/__TESTS/paypal-express-checkout/V2/examples/CheckoutModel.php
// $_checkout->getExpressCheckoutDetails('EC-4LH63646GC509300W');
// $_checkout->doExpressCheckoutPayment(array('token'=>'EC-4LH63646GC509300W','payer_id'=>'SDH6VGM35DHYA','grand_total'=>142.45));

class CheckoutModel
{

    public function setExpressCheckout()
    {
        $items = array(
            array(
                'name' => 'testing product #1',
                'description' => 'super rad description bro #1',
                'quantity' => 2,
                'unit_price' => 16.37
            ),
            array(
                'name' => 'testing product #2',
                'description' => 'super rad description bro #2',
                'quantity' => 7,
                'unit_price' => 11.33
            ),
        );

        $_checkout = new CheckoutSet();

        foreach ( $items as $item )
        {
            $data = array(
                'name'        => $item['name'],
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price']
            );

            $_checkout->setItem($data);
        }

        $_checkout->setTaxRate(0.075);
        $_checkout->setShippingAmount(22.00);
        $_checkout->setReturnUrl('http://www.miguelpelota.com/checkout/review/');
        $_checkout->setCancelUrl('http://www.miguelpelota.com/cart/');

        $response = $_checkout->setExpressCheckout();

        echo "<pre>"; print_r($response); echo "</pre><hr>"; die();

        return $response;
    }


    public function getExpressCheckoutDetails($token)
    {
        $_checkout = new CheckoutGet();

        $_checkout->setToken($token);

        $response = $_checkout->getExpressCheckoutDetails();

        echo "<pre>"; print_r($response); echo "</pre><hr>"; die();

        return $response;
    }


    public function doExpressCheckoutPayment($data)
    {
        $_checkout = new CheckoutDo();

        $_checkout->setToken($data['token']);
        $_checkout->setPayerId($data['payer_id']);
        $_checkout->setGrandTotal($data['grand_total']);

        $response = $_checkout->doExpressCheckoutPayment();

        echo "<pre>"; print_r($response); echo "</pre><hr>"; die();

        return $response;
    }

}