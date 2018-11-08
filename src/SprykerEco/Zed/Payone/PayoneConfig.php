<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\Payone\PayoneConstants;

class PayoneConfig extends AbstractBundleConfig
{
    public const PROVIDER_NAME = 'Payone';
    public const PAYMENT_METHOD_CREDIT_CARD = 'payoneCreditCard';
    public const PAYMENT_METHOD_E_WALLET = 'payoneEWallet';
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'payoneDirectDebit';
    public const PAYMENT_METHOD_ONLINE_TRANSFER = 'payoneOnlineTransfer';
    public const PAYMENT_METHOD_EPS_ONLINE_TRANSFER = 'payoneEpsOnlineTransfer';
    public const PAYMENT_METHOD_INSTANT_ONLINE_TRANSFER = 'payoneInstantOnlineTransfer';
    public const PAYMENT_METHOD_GIROPAY_ONLINE_TRANSFER = 'payoneGiropayOnlineTransfer';
    public const PAYMENT_METHOD_IDEAL_ONLINE_TRANSFER = 'payoneIdealOnlineTransfer';
    public const PAYMENT_METHOD_POSTFINANCE_EFINANCE_ONLINE_TRANSFER = 'payonePostfinanceEfinanceOnlineTransfer';
    public const PAYMENT_METHOD_POSTFINANCE_CARD_ONLINE_TRANSFER = 'payonePostfinanceCardOnlineTransfer';
    public const PAYMENT_METHOD_PRZELEWY24_ONLINE_TRANSFER = 'payonePrzelewy24OnlineTransfer';
    public const PAYMENT_METHOD_BANCONTACT_ONLINE_TRANSFER = 'payoneBancontactOnlineTransfer';
    public const PAYMENT_METHOD_PRE_PAYMENT = 'payonePrePayment';
    public const PAYMENT_METHOD_INVOICE = 'payoneInvoice';
    public const PAYMENT_METHOD_SECURITY_INVOICE = 'payoneSecurityInvoice';
    public const PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT = PayoneConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT_STATE_MACHINE;

    /**
     * Fetches API request mode from config (could be 'live' or 'test').
     *
     * @return string
     */
    public function getMode()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_MODE];
    }

    /**
     * Fetches default value for sequencenumber request parameter.
     *
     * @return string
     */
    public function getEmptySequenceNumber()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER];
    }

    /**
     * Fetches parameters that are common for all requests to Payone API.
     *
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    public function getRequestStandardParameter()
    {
        $settings = $this->get(PayoneConstants::PAYONE);
        $standardParameter = new PayoneStandardParameterTransfer();

        $standardParameter->setEncoding($settings[PayoneConstants::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameter->setMid($settings[PayoneConstants::PAYONE_CREDENTIALS_MID]);
        $standardParameter->setAid($settings[PayoneConstants::PAYONE_CREDENTIALS_AID]);
        $standardParameter->setPortalId($settings[PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameter->setKey($settings[PayoneConstants::PAYONE_CREDENTIALS_KEY]);
        $standardParameter->setPaymentGatewayUrl($settings[PayoneConstants::PAYONE_PAYMENT_GATEWAY_URL]);

        $standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

        $standardParameter->setRedirectSuccessUrl($settings[PayoneConstants::PAYONE_REDIRECT_SUCCESS_URL]);
        $standardParameter->setRedirectBackUrl($settings[PayoneConstants::PAYONE_REDIRECT_BACK_URL]);
        $standardParameter->setRedirectErrorUrl($settings[PayoneConstants::PAYONE_REDIRECT_ERROR_URL]);

        return $standardParameter;
    }

    /**
     * Fetches reference string to identify Payone payment.
     *
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $paymentTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return string
     */
    public function generatePayoneReference(PayonePaymentTransfer $paymentTransfer, SpySalesOrder $orderEntity)
    {
        return $orderEntity->getOrderReference();
    }

    /**
     * Fetches text for account statements.
     *
     * @param array $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return string
     */
    public function getNarrativeText(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        return $orderEntity->getOrderReference();
    }

    /**
     * @return string
     */
    protected function getYvesBaseUrl()
    {
        return $this->get(PayoneConstants::HOST_YVES);
    }

    /**
     * Returns path to glossary translations file.
     *
     * @return string
     */
    public function getTranslationFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . PayoneConstants::GLOSSARY_FILE_PATH;
    }

    /**
     * @return string
     */
    public function getBusinessRelation(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_BUSINESS_RELATION];
    }

    /**
     * @return array
     */
    public function getGreenScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_GREEN_SCORE_AVAILABLE_PAYMENT_METHODS];
    }


    /**
     * @return array
     */
    public function getYellowScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_YELLOW_SCORE_AVAILABLE_PAYMENT_METHODS];
    }

    /**
     * @return array
     */
    public function getRedScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_RED_SCORE_AVAILABLE_PAYMENT_METHODS];
    }

    /**
     * @return array
     */
    public function getUnknownScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS];
    }
}
