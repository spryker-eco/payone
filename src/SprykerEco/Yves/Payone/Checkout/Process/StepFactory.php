<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Checkout\Process;

use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteBridge;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use Spryker\Yves\StepEngine\Process\StepEngine;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\InitExpressCheckoutStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\LoadExpressCheckoutDetailsStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\PlaceOrderStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\SuccessStep;
use SprykerEco\Yves\Payone\Checkout\Process\Steps\SummaryStep;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator;
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

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollection
     */
    public function createStepCollection()
    {
        $stepCollection = new StepCollection(
            $this->getUrlGenerator(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );

        $stepCollection
            ->addStep($this->createInitExpressCheckoutStep())
            ->addStep($this->createLoadExpressCheckoutDetailsStep())
            ->addStep($this->createSummaryStep())
            ->addStep($this->createPlaceOrderStep())
            ->addStep($this->createSuccessStep());

        return $stepCollection;
    }

    /**
     * @return \SprykerEco\Client\Payone\PayoneClientInterface
     */
    public function getPayoneClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_PAYONE);
    }

    /**
     * @return \Spryker\Client\Shipment\ShipmentClientInterface
     */
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

    /**
     * @return \Spryker\Client\Quote\QuoteClientInterface
     */
    protected function getQuoteClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGenerator()
    {
        return $this->getApplication()['url_generator'];
    }

    /**
     * @return mixed
     */
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

    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    protected function getCalculationClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    public function getShipmentPlugins()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::PLUGIN_SHIPMENT_HANDLER);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getFlashMessanger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \Spryker\Client\Checkout\CheckoutClientInterface
     */
    protected function getCheckoutClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    protected function getCustomerClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Checkout\Process\Steps\SummaryStep
     */
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

    /**
     * @return \SprykerEco\Yves\Payone\Checkout\Process\Steps\PlaceOrderStep
     */
    protected function createPlaceOrderStep()
    {
        return new PlaceOrderStep(
            $this->getCheckoutClient(),
            $this->getFlashMessanger(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_PLACE_ORDER,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    /**
     * @return \SprykerEco\Yves\Payone\Checkout\Process\Steps\SuccessStep
     */
    protected function createSuccessStep()
    {
        return new SuccessStep(
            $this->getCustomerClient(),
            $this->getCartClient(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_SUCCESS,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    /**
     * @return \SprykerEco\Yves\Payone\Checkout\Process\Steps\LoadExpressCheckoutDetailsStep
     */
    protected function createLoadExpressCheckoutDetailsStep()
    {
        return new LoadExpressCheckoutDetailsStep(
            $this->getCartClient(),
            $this->getPayoneClient(),
            $this->createQuoteHydrator(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_LOAD_DETAILS,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

    /**
     * @return \SprykerEco\Yves\Payone\Checkout\Process\Steps\InitExpressCheckoutStep
     */
    protected function createInitExpressCheckoutStep()
    {
        return new InitExpressCheckoutStep(
            $this->getPayoneClient(),
            $this->getCartClient(),
            PayoneControllerProvider::EXPRESS_CHECKOUT_INIT,
            PayoneControllerProvider::EXPRESS_CHECKOUT_FAILURE
        );
    }

}
