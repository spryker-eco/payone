<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Money\Plugin\MoneyPlugin;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use SprykerEco\Yves\Form\Shipment\DataProvider\ShipmentFormDataProvider;
use SprykerEco\Yves\Payone\Checkout\Process\StepFactory;
use SprykerEco\Yves\Payone\Form\Checkout\FormFactory;
use SprykerEco\Yves\Payone\Form\CreditCardSubForm;
use SprykerEco\Yves\Payone\Form\DataProvider\CreditCardDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\DirectDebitDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\EpsOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\EWalletDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\GiropayOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\IdealOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\InstantOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\InvoiceDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceCardOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceEfinanceOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\PrePaymentDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\Przelewy24OnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DirectDebitSubForm;
use SprykerEco\Yves\Payone\Form\EpsOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\EWalletSubForm;
use SprykerEco\Yves\Payone\Form\GiropayOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\IdealOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\InstantOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\InvoiceSubForm;
use SprykerEco\Yves\Payone\Form\PostfinanceCardOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\PostfinanceEfinanceOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\PrePaymentForm;
use SprykerEco\Yves\Payone\Form\Przelewy24OnlineTransferSubForm;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator;
use SprykerEco\Yves\Payone\Handler\PayoneHandler;
use SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayonePrePaymentSubFormPlugin;

class PayoneFactory extends AbstractFactory
{

    /**
     * @return \SprykerEco\Yves\Payone\Form\PrePaymentForm
     */
    public function createPrePaymentForm()
    {
        return new PrePaymentForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\PrePaymentDataProvider
     */
    public function createPrePaymentFormDataProvider()
    {
        return new PrePaymentDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\InvoiceSubForm
     */
    public function createInvoiceSubForm()
    {
        return new InvoiceSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\InvoiceDataProvider
     */
    public function createInvoiceSubFormDataProvider()
    {
        return new InvoiceDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Handler\PayoneHandler
     */
    public function createPayoneHandler()
    {
        return new PayoneHandler();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayonePrePaymentSubFormPlugin
     */
    public function createPrePaymentSubFormPlugin()
    {
        return new PayonePrePaymentSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin
     */
    public function createCreditCardSubFormPlugin()
    {
        return new PayoneCreditCardSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\CreditCardSubForm
     */
    public function createCreditCardSubForm()
    {
        return new CreditCardSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\CreditCardDataProvider
     */
    public function createCreditCardSubFormDataProvider()
    {
        return new CreditCardDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\EWalletSubForm
     */
    public function createEWalletSubForm()
    {
        return new EWalletSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\EWalletDataProvider
     */
    public function createEWalletSubFormDataProvider()
    {
        return new EWalletDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    public function createDirectDebitSubForm()
    {
        return new DirectDebitSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\DirectDebitDataProvider
     */
    public function createDirectDebitSubFormDataProvider()
    {
        return new DirectDebitDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\EpsOnlineTransferSubForm
     */
    public function createEpsOnlineTransferSubForm()
    {
        return new EpsOnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\EpsOnlineTransferDataProvider
     */
    public function createEpsOnlineTransferSubFormDataProvider()
    {
        return new EpsOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\GiropayOnlineTransferSubForm
     */
    public function createGiropayOnlineTransferSubForm()
    {
        return new GiropayOnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\GiropayOnlineTransferDataProvider
     */
    public function createGiropayOnlineTransferSubFormDataProvider()
    {
        return new GiropayOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\InstantOnlineTransferSubForm
     */
    public function createInstantOnlineTransferSubForm()
    {
        return new InstantOnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\InstantOnlineTransferDataProvider
     */
    public function createInstantOnlineTransferSubFormDataProvider()
    {
        return new InstantOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\IdealOnlineTransferSubForm
     */
    public function createIdealOnlineTransferSubForm()
    {
        return new IdealOnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\IdealOnlineTransferDataProvider
     */
    public function createIdealOnlineTransferSubFormDataProvider()
    {
        return new IdealOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\PostfinanceEfinanceOnlineTransferSubForm
     */
    public function createPostfinanceEfinanceOnlineTransferSubForm()
    {
        return new PostfinanceEfinanceOnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceEfinanceOnlineTransferDataProvider
     */
    public function createPostfinanceEfinanceOnlineTransferSubFormDataProvider()
    {
        return new PostfinanceEfinanceOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\PostfinanceCardOnlineTransferSubForm
     */
    public function createPostfinanceCardOnlineTransferSubForm()
    {
        return new PostfinanceCardOnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceCardOnlineTransferDataProvider
     */
    public function createPostfinanceCardOnlineTransferSubFormDataProvider()
    {
        return new PostfinanceCardOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\PostfinanceCardOnlineTransferSubForm
     */
    public function createPrzelewy24OnlineTransferSubForm()
    {
        return new Przelewy24OnlineTransferSubForm($this->getPayoneClient());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceCardOnlineTransferDataProvider
     */
    public function createPrzelewy24OnlineTransferSubFormDataProvider()
    {
        return new Przelewy24OnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Client\Payone\PayoneClientInterface
     */
    public function getPayoneClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_PAYONE);
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\Checkout\CheckoutClientInterface
     */
    public function getCheckoutClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Shipment\ShipmentClientInterface
     */
    public function getShipmentClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::STORE);
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
     * @return \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    public function getProductBundleGrouper()
    {
        return new ProductBundleGrouper();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Checkout\Process\StepFactory
     */
    public function createStepFactory()
    {
        return new StepFactory();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepEngineInterface
     */
    public function createCheckoutProcess()
    {
        return $this->createStepFactory()->createStepEngine(
            $this->createStepFactory()->createStepCollection()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin()
    {
        return new MoneyPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\Checkout\FormFactory
     */
    public function createCheckoutFormFactory()
    {
        return new FormFactory();
    }

    /**
     * @return \Spryker\Client\Glossary\GlossaryClientInterface
     */
    public function getGlossaryClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_GLOSSARY);
    }

    /**
     * @return \SprykerEco\Yves\Form\Shipment\DataProvider\ShipmentFormDataProvider
     */
    public function createShipmentDataProvider()
    {
        return new ShipmentFormDataProvider(
            $this->getShipmentClient(),
            $this->getGlossaryClient(),
            $this->getStore(),
            $this->getMoneyPlugin()
        );
    }

}
