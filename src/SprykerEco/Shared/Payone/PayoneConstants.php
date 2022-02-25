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
    /**
     * @var string
     */
    public const COUNTRY_AT = 'AT';

    /**
     * @var string
     */
    public const COUNTRY_DE = 'DE';

    /**
     * @var string
     */
    public const COUNTRY_NL = 'NL';

    /**
     * @var string
     */
    public const COUNTRY_CH = 'CH';

    /**
     * @var string
     */
    public const PROVIDER_NAME = 'Payone';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE = 'PAYONE';

    /**
     * @var string
     */
    public const PAYONE_CREDENTIALS = 'PAYONE_CREDENTIALS';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_CREDENTIALS_ENCODING = 'PAYONE_CREDENTIALS_ENCODING';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_PAYMENT_GATEWAY_URL = 'PAYONE_PAYMENT_GATEWAY_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_CREDENTIALS_KEY = 'PAYONE_CREDENTIALS_KEY';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_CREDENTIALS_MID = 'PAYONE_CREDENTIALS_MID';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_CREDENTIALS_AID = 'PAYONE_CREDENTIALS_AID';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_CREDENTIALS_PORTAL_ID = 'PAYONE_CREDENTIALS_PORTAL_ID';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_REDIRECT_SUCCESS_URL = 'PAYONE_REDIRECT_SUCCESS_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_REDIRECT_ERROR_URL = 'PAYONE_REDIRECT_ERROR_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_REDIRECT_BACK_URL = 'PAYONE_REDIRECT_BACK_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_BUSINESS_RELATION = 'PAYONE:PAYONE_BUSINESS_RELATION';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_ADDRESS_CHECK_TYPE = 'PAYONE:PAYONE_ADDRESS_CHECK_TYPE';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_CONSUMER_SCORE_TYPE = 'PAYONE:PAYONE_CONSUMER_SCORE_TYPE';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_GREEN_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_GREEN_AVAILABLE_PAYMENT_METHODS';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_YELLOW_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_YELLOW_AVAILABLE_PAYMENT_METHODS';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_RED_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_RED_AVAILABLE_PAYMENT_METHODS';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS = 'PAYONE:PAYONE_UNKNOWN_SCORE_AVAILABLE_PAYMENT_METHODS';

    // Paypal express checkout state machine name
    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT_STATE_MACHINE = 'payonePaypalExpressCheckout';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL = 'PAYONE_STANDARD_CHECKOUT_ENTRY_POINT_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_EXPRESS_CHECKOUT_FAILURE_URL = 'PAYONE_EXPRESS_CHECKOUT_PROJECT_FAILURE_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_EXPRESS_CHECKOUT_BACK_URL = 'PAYONE_EXPRESS_CHECKOUT_PROJECT_BACK_URL';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_EMPTY_SEQUENCE_NUMBER = 'PAYONE_EMPTY_SEQUENCE_NUMBER';

    /**
     * @var string
     */
    public const PAYONE_TXACTION_APPOINTED = 'appointed';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_MODE = 'MODE';

    /**
     * @var string
     */
    public const PAYONE_MODE_TEST = 'test';

    /**
     * @var string
     */
    public const PAYONE_MODE_LIVE = 'live';

    /**
     * Path to bundle glossary file.
     *
     * @var string
     */
    public const GLOSSARY_FILE_PATH = 'Business/Internal/glossary.yml';

    /**
     * @api
     *
     * @var string
     */
    public const HOST_YVES = 'HOST_YVES';

    /**
     * @api
     *
     * @var string
     */
    public const PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA = 'PAYONE:PAYONE_PAYMENT_METHODS_WITH_OPTIONAL_PAYMENT_DATA';
}
