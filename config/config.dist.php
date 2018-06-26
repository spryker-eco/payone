<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Oms\OmsConfig;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\PayoneConfig;

$config[PayoneConstants::PAYONE] = [
    PayoneConstants::PAYONE_CREDENTIALS_ENCODING => 'UTF-8',
    PayoneConstants::PAYONE_CREDENTIALS_KEY => '',
    PayoneConstants::PAYONE_CREDENTIALS_MID => '',
    PayoneConstants::PAYONE_CREDENTIALS_AID => '',
    PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID => '',
    PayoneConstants::PAYONE_PAYMENT_GATEWAY_URL => 'https://api.pay1.de/post-gateway/',
    PayoneConstants::PAYONE_REDIRECT_SUCCESS_URL => sprintf(
        '%s/checkout/success',
        $config[ApplicationConstants::BASE_URL_YVES]
    ),
    PayoneConstants::PAYONE_REDIRECT_ERROR_URL => sprintf(
        '%s/checkout/payment',
        $config[ApplicationConstants::BASE_URL_YVES]
    ),
    PayoneConstants::PAYONE_REDIRECT_BACK_URL => sprintf(
        '%s/checkout/regular-redirect-payment-cancellation',
        $config[ApplicationConstants::BASE_URL_YVES]
    ),
    PayoneConstants::PAYONE_MODE => 'test',
    PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER => 0,
];

$config[PayoneConstants::PAYONE][PayoneConstants::HOST_YVES] = $config[ApplicationConstants::BASE_URL_YVES];

$config[PayoneConstants::PAYONE][PayoneConstants::PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL] = sprintf(
    '%s/checkout/paypal-express-checkout-entry-point',
    $config[ApplicationConstants::BASE_URL_YVES]
);

$config[PayoneConstants::PAYONE][PayoneConstants::PAYONE_EXPRESS_CHECKOUT_BACK_URL] = sprintf(
    '%s/cart',
    $config[ApplicationConstants::BASE_URL_YVES]
);

$config[PayoneConstants::PAYONE][PayoneConstants::PAYONE_EXPRESS_CHECKOUT_FAILURE_URL] = sprintf(
    '%s/cart',
    $config[ApplicationConstants::BASE_URL_YVES]
);

$config[PayoneConstants::PAYONE_BANCONTACT_AVAILABLE_COUNTRIES] = [
    'BE' => 'Belgium',
];

$config[OmsConstants::PROCESS_LOCATION] = [
    OmsConfig::DEFAULT_PROCESS_LOCATION,
    $config[KernelConstants::SPRYKER_ROOT] . '/DummyPayment/config/Zed/Oms',
    APPLICATION_VENDOR_DIR . '/spryker-eco/payone/config/Zed/Oms',
];

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    PayoneConfig::PAYMENT_METHOD_CREDIT_CARD => 'PayoneCreditCard',
    PayoneConfig::PAYMENT_METHOD_E_WALLET => 'PayoneEWallet',
    PayoneConfig::PAYMENT_METHOD_DIRECT_DEBIT => 'PayoneDirectDebit',
    PayoneConfig::PAYMENT_METHOD_EPS_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_INSTANT_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_GIROPAY_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_IDEAL_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_POSTFINANCE_CARD_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_POSTFINANCE_EFINANCE_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_PRZELEWY24_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_BANCONTACT_ONLINE_TRANSFER => 'PayoneOnlineTransfer',
    PayoneConfig::PAYMENT_METHOD_PRE_PAYMENT => 'PayonePrePayment',
    PayoneConfig::PAYMENT_METHOD_INVOICE => 'PayoneInvoice',
    PayoneConfig::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT => 'PayonePaypalExpressCheckout',
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'PayoneCreditCard',
    'PayoneEWallet',
    'PayoneDirectDebit',
    'PayoneOnlineTransfer',
    'PayonePrePayment',
    'PayoneInvoice',
    'PayonePaypalExpressCheckout',
];
