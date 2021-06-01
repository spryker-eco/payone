<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToCalculationInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToCartInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToCustomerInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToQuoteClientInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToShipmentInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface;
use SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm;
use SprykerEco\Yves\Payone\Form\BancontactOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\CashOnDeliverySubForm;
use SprykerEco\Yves\Payone\Form\CreditCardSubForm;
use SprykerEco\Yves\Payone\Form\DataProvider\BancontactOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\CashOnDeliveryDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\CreditCardDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\DirectDebitDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\EpsOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\EWalletDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\GiropayOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\IdealOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\InstantOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\InvoiceDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\KlarnaDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceCardOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\PostfinanceEfinanceOnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\PrePaymentDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\Przelewy24OnlineTransferDataProvider;
use SprykerEco\Yves\Payone\Form\DataProvider\SecurityInvoiceDataProvider;
use SprykerEco\Yves\Payone\Form\DirectDebitSubForm;
use SprykerEco\Yves\Payone\Form\EpsOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\EWalletSubForm;
use SprykerEco\Yves\Payone\Form\GiropayOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\IdealOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\InstantOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\InvoiceSubForm;
use SprykerEco\Yves\Payone\Form\KlarnaSubForm;
use SprykerEco\Yves\Payone\Form\PostfinanceCardOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\PostfinanceEfinanceOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\PrePaymentForm;
use SprykerEco\Yves\Payone\Form\Przelewy24OnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\SecurityInvoiceSubForm;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydrator;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydratorInterface;
use SprykerEco\Yves\Payone\Handler\ExpressCheckoutHandler;
use SprykerEco\Yves\Payone\Handler\ExpressCheckoutHandlerInterface;
use SprykerEco\Yves\Payone\Handler\PayoneHandler;
use SprykerEco\Yves\Payone\Handler\PayoneHandlerInterface;
use SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayonePrePaymentSubFormPlugin;

/**
 * @method \SprykerEco\Yves\Payone\PayoneConfig getConfig()
 */
class PayoneFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createPrePaymentForm(): AbstractPayoneSubForm
    {
        return new PrePaymentForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPrePaymentFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PrePaymentDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createInvoiceSubForm(): AbstractPayoneSubForm
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSecurityInvoiceSubForm(): SubFormInterface
    {
        return new SecurityInvoiceSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createKlarnaSubForm(): AbstractPayoneSubForm
    {
        return new KlarnaSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createInvoiceSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new InvoiceDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCashOnDeliverySubForm(): SubFormInterface
    {
        return new CashOnDeliverySubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCashOnDeliverySubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CashOnDeliveryDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSecurityInvoiceSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new SecurityInvoiceDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createKlarnaDataProvider(): StepEngineFormDataProviderInterface
    {
        return new KlarnaDataProvider($this->getClientStore());
    }

    /**
     * @return \SprykerEco\Yves\Payone\Handler\PayoneHandlerInterface
     */
    public function createPayoneHandler(): PayoneHandlerInterface
    {
        return new PayoneHandler();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Handler\ExpressCheckoutHandlerInterface
     */
    public function createExpressCheckoutHandler(): ExpressCheckoutHandlerInterface
    {
        return new ExpressCheckoutHandler(
            $this->getPayoneClient(),
            $this->getCartClient(),
            $this->createQuoteHydrator(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    public function createPrePaymentSubFormPlugin(): SubFormPluginInterface
    {
        return new PayonePrePaymentSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    public function createCreditCardSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneCreditCardSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createCreditCardSubForm(): AbstractPayoneSubForm
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CreditCardDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createEWalletSubForm(): AbstractPayoneSubForm
    {
        return new EWalletSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createEWalletSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new EWalletDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createDirectDebitSubForm(): AbstractPayoneSubForm
    {
        return new DirectDebitSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createDirectDebitSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new DirectDebitDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createEpsOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new EpsOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createEpsOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new EpsOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createGiropayOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new GiropayOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createGiropayOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new GiropayOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createInstantOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new InstantOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createInstantOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new InstantOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createIdealOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new IdealOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createIdealOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new IdealOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createPostfinanceEfinanceOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new PostfinanceEfinanceOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPostfinanceEfinanceOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PostfinanceEfinanceOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createPostfinanceCardOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new PostfinanceCardOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPostfinanceCardOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PostfinanceCardOnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createPrzelewy24OnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new Przelewy24OnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPrzelewy24OnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new Przelewy24OnlineTransferDataProvider();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createBancontactOnlineTransferSubForm(): AbstractPayoneSubForm
    {
        return new BancontactOnlineTransferSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createBancontactOnlineTransferSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new BancontactOnlineTransferDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerEco\Client\Payone\PayoneClientInterface
     */
    public function getPayoneClient(): PayoneClientInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_PAYONE);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Dependency\Client\PayoneToCartInterface
     */
    public function getCartClient(): PayoneToCartInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Dependency\Client\PayoneToCustomerInterface
     */
    public function getCustomerClient(): PayoneToCustomerInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Dependency\Client\PayoneToShipmentInterface
     */
    public function getShipmentClient(): PayoneToShipmentInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Dependency\Client\PayoneToCalculationInterface
     */
    public function getCalculationClient(): PayoneToCalculationInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Dependency\Client\PayoneToQuoteClientInterface
     */
    public function getQuoteClient(): PayoneToQuoteClientInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface
     */
    public function getClientStore(): PayoneToStoreClientInterface
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydratorInterface
     */
    public function createQuoteHydrator(): QuoteHydratorInterface
    {
        return new QuoteHydrator(
            $this->getShipmentClient(),
            $this->getCustomerClient(),
            $this->getCalculationClient()
        );
    }
}
