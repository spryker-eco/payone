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
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneConstants;

class PayoneConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const PROVIDER_NAME = 'Payone';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_CARD = 'payoneCreditCard';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_E_WALLET = 'payoneEWallet';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'payoneDirectDebit';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_ONLINE_TRANSFER = 'payoneOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_EPS_ONLINE_TRANSFER = 'payoneEpsOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_INSTANT_ONLINE_TRANSFER = 'payoneInstantOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_GIROPAY_ONLINE_TRANSFER = 'payoneGiropayOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_IDEAL_ONLINE_TRANSFER = 'payoneIdealOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_POSTFINANCE_EFINANCE_ONLINE_TRANSFER = 'payonePostfinanceEfinanceOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_POSTFINANCE_CARD_ONLINE_TRANSFER = 'payonePostfinanceCardOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_PRZELEWY24_ONLINE_TRANSFER = 'payonePrzelewy24OnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_BANCONTACT_ONLINE_TRANSFER = 'payoneBancontactOnlineTransfer';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_PRE_PAYMENT = 'payonePrePayment';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_INVOICE = 'payoneInvoice';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_SECURITY_INVOICE = 'payoneSecurityInvoice';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'payoneCashOnDelivery';

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT = PayoneConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT_STATE_MACHINE;

    /**
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_KLARNA = 'payoneKlarna';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ADDRESS_CHECK_BASIC = 'BA';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ADDRESS_CHECK_PERSON = 'PE';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ADDRESS_CHECK_NOT_CARRY_OUT_ADDRESS_CHECK = 'NO';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_BONIVERSUM_ADDRESS_CHECK_BASIC = 'BB';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_BONIVERSUM_ADDRESS_CHECK_PERSON = 'PB';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_SCHUFA_ADDRESS_CHECK_BASIC_SHORT = 'BS';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ARVATO_CONSUMER_SCORE_HARD_CRITERIA = 'IH';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ARVATO_CONSUMER_SCORE_ALL_CRITERIA = 'IA';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ARVATO_CONSUMER_SCORE_ALL_CRITERIA_BONUS = 'IB';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ARVATO_CONSUMER_SCORE_ALL_CRITERIA_BONUS_INFORMATION = 'IF';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_BONIVERSUM_CONSUMER_SCORE = 'CE';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_SCHUFA_CONSUMER_SCORE_SHORT = 'SFS';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_SCHUFA_CONSUMER_SCORE_MIDDLE = 'SFM';

    /**
     * @var array<string>
     */
    protected const DEFAULT_PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA = [
        PayoneApiConstants::PAYMENT_METHOD_E_WALLET,
        PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO,
        PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
    ];

    /**
     * Fetches API request mode from config (could be 'live' or 'test').
     *
     * @api
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
     * @api
     *
     * @return int
     */
    public function getEmptySequenceNumber()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER];
    }

    /**
     * Fetches parameters that are common for all requests to Payone API.
     *
     * @api
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
     * @api
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
     * @api
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
     * @api
     *
     * @return string
     */
    public function getTranslationFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . PayoneConstants::GLOSSARY_FILE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBusinessRelation(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_BUSINESS_RELATION];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getGScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_GREEN_SCORE_AVAILABLE_PAYMENT_METHODS];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getYScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_YELLOW_SCORE_AVAILABLE_PAYMENT_METHODS];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getRScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_RED_SCORE_AVAILABLE_PAYMENT_METHODS];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getUScoreAvailablePaymentMethods(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAddressCheckType(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_ADDRESS_CHECK_TYPE];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getConsumerScoreType(): string
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_CONSUMER_SCORE_TYPE];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPaymentMethodsWithOptionalPaymentData(): array
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA] ?? static::DEFAULT_PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA;
    }
}
