<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\Provider;

use Silex\Application;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;

class PayoneControllerProvider extends YvesControllerProvider
{

    const ROUTE_LOGIN = 'login';
    const CHECKOUT_PAYMENT = 'checkout-payment';
    const EXPRESS_CHECKOUT_BUTTON = 'payone-checkout-with-paypal-button';
    const EXPRESS_CHECKOUT_INIT = 'payone-paypal-express-checkout-start';
    const EXPRESS_CHECKOUT_SUCCESS = 'payone-paypal-express-checkout-success';
    const EXPRESS_CHECKOUT_FAILURE = 'payone-paypal-express-checkout-failure';
    const EXPRESS_CHECKOUT_BACK = 'payone-paypal-express-checkout-back';
    const EXPRESS_CHECKOUT_PLACE_ORDER = 'payone-paypal-express-checkout-place-order';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController('/payone', 'payone-index', 'Payone', 'index', 'index')->method('POST');
        $this->createController('/payone/getfile', 'payone-getfile', 'payone', 'index', 'getFile')->method('GET|POST');
        $this->createController('/payone/regular-redirect-payment-cancellation', 'payone-cancel-redirect', 'Payone', 'index', 'cancelRedirect')->method('GET');
        $this->createController('/payone/getinvoice', 'payone-getinvoice', 'Payone', 'index', 'getInvoice')->method('GET');
        $this->createController(
            '/payone/checkout-with-paypal-button',
            static::EXPRESS_CHECKOUT_BUTTON,
            'payone',
            'expressCheckout',
            'checkoutWithPaypalButton'
        )->method('GET');

        $this->createController(
            '/payone/paypal-express-checkout-init',
            static::EXPRESS_CHECKOUT_INIT,
            'payone',
            'expressCheckout',
            'initPaypalExpressCheckout'
        )->method('GET');

        $this->createController(
            '/payone/expresscheckout/success',
            static::EXPRESS_CHECKOUT_SUCCESS,
            'payone',
            'expressCheckout',
            'success'
        )->method('GET');

        $this->createController(
            '/payone/expresscheckout/failure',
            static::EXPRESS_CHECKOUT_FAILURE,
            'payone',
            'expressCheckout',
            'failure'
        )->method('GET');

        $this->createController(
            '/payone/expresscheckout/back',
            static::EXPRESS_CHECKOUT_BACK,
            'payone',
            'expressCheckout',
            'back'
        )->method('GET');

        $this->createController(
            '/payone/expresscheckout/place-order',
            static::EXPRESS_CHECKOUT_PLACE_ORDER,
            'payone',
            'expressCheckout',
            'placeOrder'
        )->method('GET');
    }

}
