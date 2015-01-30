<?php

namespace Miguelpelota\PaypalExpressCheckout\V2;

class CheckoutSet extends CheckoutCore
{

    private $method          = 'SetExpressCheckout';
    private $items           = array();
    private $shipping_amount = 0;
    private $tax_rate        = 0;
    private $return_url      = '';
    private $cancel_url      = '';


    public function setExpressCheckout()
    {
        $post_vars['METHOD'] = $this->method;

        $subtotal = 0;

        $i = 0;

        foreach ( $this->items as $item )
        {
            $post_vars['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
            $post_vars['L_PAYMENTREQUEST_0_AMT'  . $i] = $item['unit_price'];
            $post_vars['L_PAYMENTREQUEST_0_QTY'  . $i] = $item['quantity'];
            $post_vars['L_PAYMENTREQUEST_0_DESC' . $i] = $item['description'];

            $subtotal += $item['unit_price'] * $item['quantity'];

            $i++;
        }

        $post_vars['PAYMENTREQUEST_0_ITEMAMT']     = round($subtotal, 2);
        $post_vars['PAYMENTREQUEST_0_TAXAMT']      = round($this->tax_rate * $post_vars['PAYMENTREQUEST_0_ITEMAMT'], 2);
        $post_vars['PAYMENTREQUEST_0_SHIPPINGAMT'] = $this->shipping_amount;
        $post_vars['PAYMENTREQUEST_0_AMT']         = round($post_vars['PAYMENTREQUEST_0_ITEMAMT'] + $post_vars['PAYMENTREQUEST_0_TAXAMT'] + $post_vars['PAYMENTREQUEST_0_SHIPPINGAMT'], 2);

        $post_vars['RETURNURL'] = $this->return_url;
        $post_vars['CANCELURL'] = $this->cancel_url;

        $response = $this->paypalCurl($post_vars);

        $response['redirect_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $response['TOKEN'];

        return $response;
    }


    public function setItem($item)
    {
        $this->items[] = $item;
    }


    public function setShippingAmount($shipping_amount)
    {
        $this->shipping_amount = $shipping_amount;
    }


    public function setTaxRate($tax_rate)
    {
        $this->tax_rate = $tax_rate;
    }


    public function setReturnUrl($return_url)
    {
        $this->return_url = $return_url;
    }


    public function setCancelUrl($cancel_url)
    {
        $this->cancel_url = $cancel_url;
    }

}