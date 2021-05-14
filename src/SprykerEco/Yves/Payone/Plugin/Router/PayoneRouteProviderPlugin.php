<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PayoneRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_PAYONE_INDEX = 'payone-index';
    protected const ROUTE_PAYONE_GET_FILE = 'payone-getfile';
    protected const ROUTE_PAYONE_CANCEL_REDIRECT = 'payone-cancel-redirect';
    protected const ROUTE_PAYONE_GET_INVOICE = 'payone-getinvoice';
    protected const ROUTE_PAYONE_PAYMENT_SUCCESS = 'payone-payment-success';
    protected const ROUTE_PAYONE_PAYMENT_FAILURE = 'payone-payment-failure';

    protected const ROUTE_PAYONE_EXPRESS_CHECKOUT_BUTTON = 'payone-checkout-with-paypal-button';
    protected const ROUTE_PAYONE_EXPRESS_CHECKOUT_INIT = 'payone-paypal-express-checkout-init';
    protected const ROUTE_PAYONE_EXPRESS_CHECKOUT_FAILURE = 'payone-paypal-express-checkout-failure';
    protected const ROUTE_PAYONE_EXPRESS_CHECKOUT_BACK = 'payone-paypal-express-checkout-back';
    protected const ROUTE_PAYONE_EXPRESS_CHECKOUT_LOAD_DETAILS = 'payone-paypal-express-checkout-load-details';

    protected const ROUTE_NAME_PAYONE_KLARNA_GET_TOKEN = 'payone-klarna-get-token';

    /**
     * {@inheritDoc}
     * - Adds Payone specific Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addPayoneIndexRoute($routeCollection);
        $routeCollection = $this->addPayoneGetFileRoute($routeCollection);
        $routeCollection = $this->addPayoneCancelRedirectRoute($routeCollection);
        $routeCollection = $this->addPayoneGetInvoiceRoute($routeCollection);
        $routeCollection = $this->addPayonePaymentSuccessRoute($routeCollection);
        $routeCollection = $this->addPayonePaymentFailureRoute($routeCollection);
        $routeCollection = $this->addPayoneExpressCheckoutButtonRoute($routeCollection);
        $routeCollection = $this->addPayoneExpressCheckoutInitRoute($routeCollection);
        $routeCollection = $this->addPayoneExpressCheckoutFailureRoute($routeCollection);
        $routeCollection = $this->addPayoneExpressCheckoutBackRoute($routeCollection);
        $routeCollection = $this->addPayoneExpressCheckoutLoadDetailsRoute($routeCollection);
        $routeCollection = $this->addPayoneKlarnaGetTokenRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneIndexRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone', 'Payone', 'Index', 'indexAction');
        $route = $route->setMethods(['POST']);
        $routeCollection->add(static::ROUTE_PAYONE_INDEX, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneGetFileRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/getfile', 'Payone', 'Index', 'getFileAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::ROUTE_PAYONE_GET_FILE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneCancelRedirectRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/regular-redirect-payment-cancellation', 'Payone', 'Index', 'cancelRedirectAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_CANCEL_REDIRECT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneGetInvoiceRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/getinvoice', 'Payone', 'Index', 'getInvoiceAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_GET_INVOICE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneExpressCheckoutButtonRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/checkout-with-paypal-button', 'Payone', 'ExpressCheckout', 'checkoutWithPaypalButtonAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_EXPRESS_CHECKOUT_BUTTON, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneExpressCheckoutInitRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/paypal-express-checkout-init', 'Payone', 'ExpressCheckout', 'initPaypalExpressCheckoutAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_EXPRESS_CHECKOUT_INIT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneExpressCheckoutFailureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/expresscheckout/failure', 'Payone', 'ExpressCheckout', 'failureAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_EXPRESS_CHECKOUT_FAILURE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneExpressCheckoutBackRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/expresscheckout/back', 'Payone', 'ExpressCheckout', 'backAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_EXPRESS_CHECKOUT_BACK, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneExpressCheckoutLoadDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/expresscheckout/load-details', 'Payone', 'ExpressCheckout', 'loadPaypalExpressCheckoutDetailsAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_EXPRESS_CHECKOUT_LOAD_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerEco\Yves\Payone\Controller\KlarnaController::createPayoneKlarnaStartSessionRequestTransfer()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayoneKlarnaGetTokenRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/get-token', 'Payone', 'Klarna', 'getTokenAction');
        $route = $route->setMethods(['POST']);
        $routeCollection->add(static::ROUTE_NAME_PAYONE_KLARNA_GET_TOKEN, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayonePaymentSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/payment-success', 'Payone', 'Index', 'paymentSuccessAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_PAYMENT_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPayonePaymentFailureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/payone/payment-failure', 'Payone', 'Index', 'paymentFailureAction');
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_PAYONE_PAYMENT_FAILURE, $route);

        return $routeCollection;
    }
}
