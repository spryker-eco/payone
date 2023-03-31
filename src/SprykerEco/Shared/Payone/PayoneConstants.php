<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PayoneConstants
{
    public const COUNTRY_AT = 'AT';
    public const COUNTRY_DE = 'DE';
    public const COUNTRY_NL = 'NL';
    public const COUNTRY_CH = 'CH';

    public const PROVIDER_NAME = 'Payone';
    public const PAYONE = 'PAYONE';
    public const PAYONE_CREDENTIALS = 'PAYONE_CREDENTIALS';
    public const PAYONE_CREDENTIALS_ENCODING = 'PAYONE_CREDENTIALS_ENCODING';
    public const PAYONE_PAYMENT_GATEWAY_URL = 'PAYONE_PAYMENT_GATEWAY_URL';
    public const PAYONE_CREDENTIALS_KEY = 'PAYONE_CREDENTIALS_KEY';
    public const PAYONE_CREDENTIALS_MID = 'PAYONE_CREDENTIALS_MID';
    public const PAYONE_CREDENTIALS_AID = 'PAYONE_CREDENTIALS_AID';
    public const PAYONE_CREDENTIALS_PORTAL_ID = 'PAYONE_CREDENTIALS_PORTAL_ID';
    public const PAYONE_REDIRECT_SUCCESS_URL = 'PAYONE_REDIRECT_SUCCESS_URL';
    public const PAYONE_REDIRECT_ERROR_URL = 'PAYONE_REDIRECT_ERROR_URL';
    public const PAYONE_REDIRECT_BACK_URL = 'PAYONE_REDIRECT_BACK_URL';
    public const PAYONE_BUSINESS_RELATION = 'PAYONE:PAYONE_BUSINESS_RELATION';

    public const PAYONE_ADDRESS_CHECK_TYPE = 'PAYONE:PAYONE_ADDRESS_CHECK_TYPE';
    public const PAYONE_CONSUMER_SCORE_TYPE = 'PAYONE:PAYONE_CONSUMER_SCORE_TYPE';

    public const PAYONE_GREEN_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_GREEN_AVAILABLE_PAYMENT_METHODS';
    public const PAYONE_YELLOW_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_YELLOW_AVAILABLE_PAYMENT_METHODS';
    public const PAYONE_RED_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_RED_AVAILABLE_PAYMENT_METHODS';
    public const PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS';

    // Paypal express checkout state machine name
    public const PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT_STATE_MACHINE = 'payonePaypalExpressCheckout';

    public const PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL = 'PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL';
    public const PAYONE_EXPRESS_CHECKOUT_FAILURE_URL = 'PAYONE_EXPRESS_CHECKOUT_PROJECT_FAILURE_URL';
    public const PAYONE_EXPRESS_CHECKOUT_BACK_URL = 'PAYONE_EXPRESS_CHECKOUT_PROJECT_BACK_URL';

    public const PAYONE_EMPTY_SEQUENCE_NUMBER = 'PAYONE_EMPTY_SEQUENCE_NUMBER';

    public const PAYONE_TXACTION_APPOINTED = 'appointed';

    public const PAYONE_MODE = 'MODE';
    public const PAYONE_MODE_TEST = 'test';
    public const PAYONE_MODE_LIVE = 'live';

    /**
     * Path to bundle glossary file.
     *
     * @deprecated No longer used. Will be removed without replacement.
     */
    public const GLOSSARY_FILE_PATH = 'Business/Internal/glossary.yml';

    public const HOST_YVES = 'HOST_YVES';

    public const PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA = 'PAYONE:PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA';
}
