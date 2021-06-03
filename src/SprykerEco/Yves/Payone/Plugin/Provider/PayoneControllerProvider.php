<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\Provider;

use Silex\Application;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;

/**
 * @deprecated Use `\SprykerEco\Yves\Payone\Plugin\Router\PayoneRouteProviderPlugin` instead.
 */
class PayoneControllerProvider extends YvesControllerProvider
{
    public const ROUTE_LOGIN = 'login';
    public const CHECKOUT_PAYMENT = 'checkout-payment';
    public const EXPRESS_CHECKOUT_BUTTON = 'payone-checkout-with-paypal-button';
    public const EXPRESS_CHECKOUT_INIT = 'payone-paypal-express-checkout-init';
    public const EXPRESS_CHECKOUT_FAILURE = 'payone-paypal-express-checkout-failure';
    public const EXPRESS_CHECKOUT_BACK = 'payone-paypal-express-checkout-back';
    public const EXPRESS_CHECKOUT_LOAD_DETAILS = 'payone-paypal-express-checkout-load-details';

    public const EXPRESS_CHECKOUT_BUTTON_PATH = '/payone/checkout-with-paypal-button';
    public const EXPRESS_CHECKOUT_INIT_PATH = '/payone/paypal-express-checkout-init';
    public const EXPRESS_CHECKOUT_FAILURE_PATH = '/payone/expresscheckout/failure';
    public const EXPRESS_CHECKOUT_BACK_PATH = '/payone/expresscheckout/back';
    public const EXPRESS_CHECKOUT_LOAD_DETAILS_PATH = '/payone/expresscheckout/load-details';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    //phpcs:ignore
    protected function defineControllers(Application $app)
    {
        $this->createController('/payone', 'payone-index', 'Payone', 'index', 'index')->method('POST');
        $this->createController('/payone/getfile', 'payone-getfile', 'payone', 'index', 'getFile')->method('GET|POST');
        $this->createController('/payone/regular-redirect-payment-cancellation', 'payone-cancel-redirect', 'Payone', 'index', 'cancelRedirect')->method('GET');
        $this->createController('/payone/getinvoice', 'payone-getinvoice', 'Payone', 'index', 'getInvoice')->method('GET');
        $this->createController(
            static::EXPRESS_CHECKOUT_BUTTON_PATH,
            static::EXPRESS_CHECKOUT_BUTTON,
            'payone',
            'expressCheckout',
            'checkoutWithPaypalButton'
        )->method('GET');

        $this->createController(
            static::EXPRESS_CHECKOUT_INIT_PATH,
            static::EXPRESS_CHECKOUT_INIT,
            'payone',
            'expressCheckout',
            'initPaypalExpressCheckout'
        )->method('GET');

        $this->createController(
            static::EXPRESS_CHECKOUT_LOAD_DETAILS_PATH,
            static::EXPRESS_CHECKOUT_LOAD_DETAILS,
            'payone',
            'expressCheckout',
            'loadPaypalExpressCheckoutDetails'
        )->method('GET');

        $this->createController(
            static::EXPRESS_CHECKOUT_FAILURE_PATH,
            static::EXPRESS_CHECKOUT_FAILURE,
            'payone',
            'expressCheckout',
            'failure'
        )->method('GET');

        $this->createController(
            static::EXPRESS_CHECKOUT_BACK_PATH,
            static::EXPRESS_CHECKOUT_BACK,
            'payone',
            'expressCheckout',
            'back'
        )->method('GET');
    }
}
