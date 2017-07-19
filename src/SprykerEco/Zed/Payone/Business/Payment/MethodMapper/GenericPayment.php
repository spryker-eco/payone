<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;


use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\Payment\BasePaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\Payment\GenericPaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

class GenericPayment implements GenericPaymentMethodMapperInterface, BasePaymentMethodMapperInterface
{

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    private $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    private $sequenceNumberProvider;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator
     */
    private $urlHmacGenerator;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeConfig;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function mapQuoteToGenericRequest(QuoteTransfer $quoteTransfer)
    {
        $genericPayment = new GenericPaymentContainer();

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAction(PayoneApiConstants::PAYONE_EXPRESS_CHECKOUT_SET_ACTION);

        $genericPayment->setPaydata($paydataContainer);
        $genericPayment->setAid($this->getStandardParameter()->getAid());
        $genericPayment->setClearingType(PayoneApiConstants::CLEARING_TYPE_E_WALLET);
        $genericPayment->setCurrency($this->getStandardParameter()->getCurrency());
        $genericPayment->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $genericPayment->setWalletType(PayoneApiConstants::E_WALLET_TYPE_PAYPAL);

        //TODO: create custom actions in order to handle the success and falure urs because we don't have order here.
        $orderReference = "#fake";
        $sig = $this->getUrlHmacGenerator()->hash($orderReference, $this->getStandardParameter()->getKey());
        $params = '?orderReference=' . $orderReference . '&sig=' . $sig;

        $genericPayment->setSuccessUrl($this->getStandardParameter()->getRedirectSuccessUrl() . $params);
        $genericPayment->setBackUrl($this->getStandardParameter()->getRedirectBackUrl() . $params);
        $genericPayment->setErrorUrl($this->getStandardParameter()->getRedirectErrorUrl() . $params);

        return $genericPayment;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterTransfer $standardParameter)
    {
        $this->standardParameter = $standardParameter;
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        return $this->standardParameter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXTERNAL_CHECKOUT;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     *
     * @return void
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider)
    {
        $this->sequenceNumberProvider = $sequenceNumberProvider;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected function getSequenceNumberProvider()
    {
        return $this->sequenceNumberProvider;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator $urlHmacGenerator
     *
     * @return void
     */
    public function setUrlHmacGenerator(UrlHmacGenerator $urlHmacGenerator)
    {
        $this->urlHmacGenerator = $urlHmacGenerator;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator
     */
    protected function getUrlHmacGenerator()
    {
        return $this->urlHmacGenerator;
    }
}
