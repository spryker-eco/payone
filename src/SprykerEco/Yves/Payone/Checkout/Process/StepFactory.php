<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Checkout\Process;

use Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteBridge;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\InitExpressCheckoutStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\LoadExpressCheckoutDetailsStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\PlaceOrderStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\SuccessStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\SummaryStep;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use Spryker\Yves\StepEngine\Process\StepEngine;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator;
use SprykerEco\Yves\Payone\Handler\ExpressCheckoutHandler;
use SprykerEco\Yves\Payone\PayoneDependencyProvider;
use SprykerEco\Yves\Payone\Plugin\Provider\PayoneControllerProvider;

class StepFactory extends AbstractFactory
{

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     *
     * @return \Spryker\Yves\StepEngine\Process\StepEngine
     */
    public function createStepEngine(StepCollectionInterface $stepCollection)
    {
        return new StepEngine($stepCollection, $this->createDataContainer());
    }

    public function createStepCollection()
    {
        $stepCollection = new StepCollection(
            $this->getUrlGenerator(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );

        $stepCollection
            ->addStep($this->createLoadExpressCheckoutDetailsStep())
            ->addStep($this->createSummaryStep())
            ->addStep($this->createPlaceOrderStep())
            ->addStep($this->createSuccessStep());

        return $stepCollection;
    }

    /**
     * @return \SprykerEco\Yves\Payone\Handler\ExpressCheckoutHandler
     */
    public function createExpressCheckoutHandler()
    {
        return new ExpressCheckoutHandler(
            $this->getPayoneClient(),
            $this->getCartClient(),
            $this->getCheckoutClient(),
            $this->createQuoteHydrator()
        );
    }

    /**
     * @return \SprykerEco\Client\Payone\PayoneClientInterface
     */
    public function getPayoneClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_PAYONE);
    }

    public function getShipmentClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator
     */
    public function createQuoteHydrator()
    {
        return new QuoteHydrator(
            $this->getShipmentClient(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \Spryker\Yves\Checkout\DataContainer\DataContainer
     */
    protected function createDataContainer()
    {
        return new DataContainer(new CheckoutToQuoteBridge($this->getQuoteClient()));
    }

    protected function getQuoteClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_QUOTE);
    }

    protected function getUrlGenerator()
    {
        return $this->getApplication()['url_generator'];
    }

    protected function getApplication()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    protected function createProductBundleGrouper()
    {
        return new ProductBundleGrouper();
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    protected function getCartClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CART);
    }

    protected function getCalculationClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CALCULATION);
    }

    public function getShipmentPlugins()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::PLUGIN_SHIPMENT_HANDLER);
    }

    public function getFlashMessanger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    protected function getCheckoutClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CHECKOUT);
    }

    protected function getCustomerClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CUSTOMER);
    }

    protected function createSummaryStep()
    {
        return new SummaryStep(
            $this->createProductBundleGrouper(),
            $this->getCalculationClient(),
            $this->getShipmentPlugins(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_SUMMARY,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    protected function createPlaceOrderStep()
    {
        return new PlaceOrderStep(
            $this->getCheckoutClient(),
            $this->getFlashMessanger(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_PLACE_ORDER,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    protected function createSuccessStep()
    {
        return new SuccessStep(
            $this->getCustomerClient(),
            $this->getCartClient(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_SUCCESS,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    protected function createLoadExpressCheckoutDetailsStep()
    {
        return new LoadExpressCheckoutDetailsStep(
            $this->createExpressCheckoutHandler(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_LOAD_DETAILS,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    protected function createInitExpressCheckoutStep()
    {
        return new InitExpressCheckoutStep(
            PayoneControllerProvider::EXPRESS_CHECKOUT_INIT,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

}
