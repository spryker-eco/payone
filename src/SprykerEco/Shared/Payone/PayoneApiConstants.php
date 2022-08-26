<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PayoneApiConstants
{
    // GENERAL

    /**
     * @var string
     */
    public const PROVIDER_NAME = 'payone';

    // MODE

    /**
     * @var string
     */
    public const MODE_TEST = 'test';

    /**
     * @var string
     */
    public const MODE_LIVE = 'live';

    // VERSIONS

    /**
     * @var string
     */
    public const API_VERSION_3_8 = '3.8';

    /**
     * @var string
     */
    public const API_VERSION_3_9 = '3.9';

    // INTEGRATOR

    /**
     * @var string
     */
    public const INTEGRATOR_NAME_SPRYKER = 'spryker';

    /**
     * @var string
     */
    public const INTEGRATOR_VERSION_3_0_0 = '3.0.0';

    // SOLUTION

    /**
     * @var string
     */
    public const SOLUTION_NAME_SPRYKER = 'spryker';

    /**
     * @var string
     */
    public const SOLUTION_VERSION_3_0_0 = '3.0.0';

    // PAYMENT METHODS

    // credit/debit card methods
    /**
     * @var string
     */
    public const PAYMENT_METHOD_CREDITCARD = 'payment.payone.creditcard';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_CREDITCARD_PSEUDO = 'payment.payone.creditcard';

    // e-wallet methods
    /**
     * @var string
     */
    public const PAYMENT_METHOD_E_WALLET = 'payment.payone.e_wallet';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT = 'payment.payone.paypal_external_checkout';

    // bank account based methods
    /**
     * @var string
     */
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'payment.payone.direct_debit';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_INVOICE = 'payment.payone.invoice';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_SECURITY_INVOICE = 'payment.payone.security_invoice';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PREPAYMENT = 'payment.payone.prepayment';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'payment.payone.cash_on_delivery';

    // online transfer methods
    /**
     * @var string
     */
    public const PAYMENT_METHOD_ONLINE_BANK_TRANSFER = 'payment.payone.online_bank_transfer';

    // klarna payments
    /**
     * @var string
     */
    public const PAYMENT_METHOD_KLARNA = 'payment.payone.klarna';

    /**
     * @var string
     */
    public const PAYMENT_KLARNA_START_SESSION_ACTION = 'start_session';

    // CLEARING TYPE

    /**
     * @var string
     */
    public const CLEARING_TYPE_DIRECT_DEBIT = 'elv';

    /**
     * @var string
     */
    public const CLEARING_TYPE_CREDIT_CARD = 'cc';

    /**
     * @var string
     */
    public const CLEARING_TYPE_PREPAYMENT = 'vor';

    /**
     * @var string
     */
    public const CLEARING_TYPE_INVOICE = 'rec';

    /**
     * @var string
     */
    public const CLEARING_TYPE_SECURITY_INVOICE = 'rec';

    /**
     * @var string
     */
    public const CLEARING_TYPE_ONLINE_BANK_TRANSFER = 'sb';

    /**
     * @var string
     */
    public const CLEARING_TYPE_CASH_ON_DELIVERY = 'cod';

    /**
     * @var string
     */
    public const CLEARING_TYPE_E_WALLET = 'wlt';

    /**
     * @var string
     */
    public const CLEARING_TYPE_FINANCING = 'fnc';

    //CLEARING SUBTYPE
    /**
     * @var string
     */
    public const CLEARING_SUBTYPE_SECURITY_INVOICE = 'POV';

    // TXACTION

    // Defined in TransactionStatusConstants

    // WALLET TYPE

    /**
     * @var string
     */
    public const E_WALLET_TYPE_PAYPAL = 'PPE';

    /**
     * @var string
     */
    public const E_WALLET_TYPE_PAY_DIRECT = 'PDT';

    // USE CUSTOMER DATA

    /**
     * @var string
     */
    public const USE_CUSTOMER_DATA_YES = 'yes';

    /**
     * @var string
     */
    public const USE_CUSTOMER_DATA_NO = 'no';

    // STORE CARD DATA

    /**
     * @var string
     */
    public const STORE_CARD_DATA_YES = 'yes';

    /**
     * @var string
     */
    public const STORE_CARD_DATA_NO = 'no';

    // SHIPPING PROVIDER

    /**
     * @var string
     */
    public const SHIPPING_PROVIDER_DHL = 'DHL';

    /**
     * @var string
     */
    public const SHIPPING_PROVIDER_BARTOLINI = 'BRT';

    // FINANCING SETTLE ACCOUNT

    /**
     * @var string
     */
    public const SETTLE_ACCOUNT_YES = 'yes';

    /**
     * @var string
     */
    public const SETTLE_ACCOUNT_NO = 'no';

    /**
     * @var string
     */
    public const SETTLE_ACCOUNT_AUTO = 'auto';

    // RESPONSE TYPE

    /**
     * @var string
     */
    public const RESPONSE_TYPE_APPROVED = 'APPROVED';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_REDIRECT = 'REDIRECT';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_JSON = 'JSON';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_VALID = 'VALID';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_INVALID = 'INVALID';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_BLOCKED = 'BLOCKED';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_ENROLLED = 'ENROLLED';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_ERROR = 'ERROR';

    /**
     * @var string
     */
    public const RESPONSE_TYPE_TIMEOUT = 'TIMEOUT';

    // REQUEST ENCODING

    /**
     * @var string
     */
    public const REQUEST_ENCODING = 'UTF-8';

    // REQUEST TYPE

    /**
     * @var string
     */
    public const REQUEST_TYPE_PREAUTHORIZATION = 'preauthorization';

    /**
     * @var string
     */
    public const REQUEST_TYPE_AUTHORIZATION = 'authorization';

    /**
     * @var string
     */
    public const REQUEST_TYPE_CAPTURE = 'capture';

    /**
     * @var string
     */
    public const REQUEST_TYPE_REFUND = 'refund';

    /**
     * @var string
     */
    public const REQUEST_TYPE_DEBIT = 'debit';

    /**
     * @var string
     */
    public const REQUEST_TYPE_3DSECURE_CHECK = '3dscheck';

    /**
     * @var string
     */
    public const REQUEST_TYPE_ADDRESSCHECK = 'addresscheck';

    /**
     * @var string
     */
    public const REQUEST_TYPE_CONSUMERSCORE = 'consumerscore';

    /**
     * @var string
     */
    public const REQUEST_TYPE_BANKACCOUNTCHECK = 'bankaccountcheck';

    /**
     * @var string
     */
    public const REQUEST_TYPE_CREDITCARDCHECK = 'creditcardcheck';

    /**
     * @var string
     */
    public const REQUEST_TYPE_GETINVOICE = 'getinvoice';

    /**
     * @var string
     */
    public const REQUEST_TYPE_GETSECURITYINVOICE = 'getsecurityinvoice';

    /**
     * @var string
     */
    public const REQUEST_TYPE_MANAGEMANDATE = 'managemandate';

    /**
     * @var string
     */
    public const REQUEST_TYPE_GETFILE = 'getfile';

    /**
     * @var string
     */
    public const REQUEST_TYPE_GENERICPAYMENT = 'genericpayment';

    // ONLINE BANK TRANSFER TYPE

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_INSTANT_MONEY_TRANSFER = 'PNT';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_GIROPAY = 'GPY';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_EPS_ONLINE_BANK_TRANSFER = 'EPS';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_EFINANCE = 'PFF';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_CARD = 'PFC';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_IDEAL = 'IDL';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_PRZELEWY24 = 'P24';

    /**
     * @var string
     */
    public const ONLINE_BANK_TRANSFER_TYPE_BANCONTACT = 'BCT';

    // FAILED CAUSE

    /**
     * Specification:
     * - soc Insufficient funds.
     *
     * @var string
     */
    public const FAILED_CAUSE_INSUFFICIENT_FUNDS = 'soc';

    /**
     * Specification:
     * - cka Account expired.
     *
     * @var string
     */
    public const FAILED_CAUSE_ACCOUNT_EXPIRED = 'cka';

    /**
     * Specification:
     * - uan Account no. / name not idential, incorrect or savings account
     *
     * @var string
     */
    public const FAILED_CAUSE_UNKNOWN_ACCOUNT_NAME = 'uan';

    /**
     * Specification:
     * - ndd No direct debit
     *
     * @var string
     */
    public const FAILED_CAUSE_NO_DIRECT_DEBIT = 'ndd';

    /**
     * Specification:
     * - rcl Recall
     *
     * @var string
     */
    public const FAILED_CAUSE_RECALL = 'rcl';

    /**
     * Specification:
     * - obj Objection
     *
     * @var string
     */
    public const FAILED_CAUSE_OBJECTION = 'obj';

    /**
     * Specification:
     * - ret Return
     *
     * @var string
     */
    public const FAILED_CAUSE_RETURNS = 'ret';

    /**
     * Specification:
     * - nelv Debit cannot be collected
     *
     * @var string
     */
    public const FAILED_CAUSE_DEBIT_NOT_COLLECTABLE = 'nelv';

    /**
     * Specification:
     * - cb Credit card chargeback
     *
     * @var string
     */
    public const FAILED_CAUSE_CREDITCARD_CHARGEBACK = 'cb';

    /**
     * Specification:
     * - ncc Credit card cannot be collected
     *
     * @var string
     */
    public const FAILED_CAUSE_CREDITCARD_NOT_COLLECTABLE = 'ncc';

    // INVOICING ITEM TYPE

    /**
     * @var string
     */
    public const INVOICING_ITEM_TYPE_GOODS = 'goods';

    /**
     * @var string
     */
    public const INVOICING_ITEM_TYPE_SHIPMENT = 'shipment';

    /**
     * @var string
     */
    public const INVOICING_ITEM_TYPE_HANDLING = 'handling';

    /**
     * @var string
     */
    public const INVOICING_ITEM_TYPE_VOUCHER = 'voucher';

    // DELIVERY MODE

    /**
     * @var string
     */
    public const DELIVERY_MODE_POST = 'M';

    /**
     * @var string
     */
    public const DELIVERY_MODE_PDF = 'P';

    /**
     * @var string
     */
    public const DELIVERY_MODE_NONE = 'N';

    // FINANCING TYPE

    /**
     * @var string
     */
    public const FINANCING_TYPE_BSV = 'BSV'; // BILLSAFE

    /**
     * @var string
     */
    public const FINANCING_TYPE_CFR = 'CFR'; // COMMERZ FINANZ

    // ECOMMERCE MODE

    /**
     * @var string
     */
    public const ECOMMERCE_MODE_INTERNET = 'internet';

    /**
     * @var string
     */
    public const ECOMMERCE_MODE_SECURE3D = '3dsecure';

    /**
     * @var string
     */
    public const ECOMMERCE_MODE_MOTO = 'moto';

    // DEBIT TRANSACTION TYPE

    /**
     * Specification:
     * - RL: Rücklastschriftgebühr
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_DIRECT_DEBIT_REFUND_FEE = 'RL';

    /**
     * Specification:
     * - MG: Mahngebühren
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_DUNNING_CHARGE = 'MG';

    /**
     * Specification:
     * - VZ: Verzugszinsen
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_DEFAULT_INTEREST = 'VZ';

    /**
     * Specification:
     * - VD: Versandkosten
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_SHIPPING_COSTS = 'VD';

    /**
     * Specification:
     * - FD: Forderung (default bei amount >0)
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_PAYMENT_REQUEST = 'FD';

    /**
     * Specification:
     * - GT: Gutschrift (default bei amount <0)
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_CREDIT = 'GT';

    /**
     * Specification:
     * - RT: Retoure
     *
     * @var string
     */
    public const DEBIT_TRANSACTION_TYPE_RETURNS = 'RT';

    // PERSONAL DATA

    /**
     * @var string
     */
    public const PERSONAL_GENDER_MALE = 'm';

    /**
     * @var string
     */
    public const PERSONAL_GENDER_FEMALE = 'f';

    // CREDITCARD TYPE

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_VISA = 'V';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_MASTERCARD = 'M';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_AMEX = 'A';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_DINERS = 'D';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_JCB = 'J';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_MAESTRO_INTERNATIONAL = 'O';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_MAESTRO_UK = 'U';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_DISCOVER = 'C';

    /**
     * @var string
     */
    public const CREDITCARD_TYPE_CARTE_BLEUE = 'B';

    // CONSUMER SCORE

    /**
     * @var string
     */
    public const CONSUMER_SCORE_GREEN = 'G';

    /**
     * @var string
     */
    public const CONSUMER_SCORE_YELLOW = 'Y';

    /**
     * @var string
     */
    public const CONSUMER_SCORE_RED = 'R';

    // CONSUMER SCORE TYPE

    /**
     * @var string
     */
    public const CONSUMER_SCORE_TYPE_INFOSCORE_HARD = 'IH';

    /**
     * @var string
     */
    public const CONSUMER_SCORE_TYPE_INFOSCORE_ALL = 'IA';

    /**
     * @var string
     */
    public const CONSUMER_SCORE_TYPE_INFOSCORE_ALL_BONI = 'IB';

    // CAPTURE MODE

    /**
     * @var string
     */
    public const CAPTURE_MODE_COMPLETED = 'completed';

    /**
     * @var string
     */
    public const CAPTURE_MODE_NOTCOMPLETED = 'notcompleted';

    // AVS RESULT

    /**
     * @var string
     */
    public const AVS_RESULT_A = 'A';

    /**
     * @var string
     */
    public const AVS_RESULT_F = 'F';

    /**
     * @var string
     */
    public const AVS_RESULT_N = 'N';

    /**
     * @var string
     */
    public const AVS_RESULT_U = 'U';

    /**
     * @var string
     */
    public const AVS_RESULT_Z = 'Z';

    // BANK ACCOUNT CHECK TYPE

    /**
     * @var string
     */
    public const BANK_ACCOUNT_CHECK_TYPE_REGULAR = '0';

    /**
     * @var string
     */
    public const BANK_ACCOUNT_CHECK_TYPE_POS_BLACKLIST = '1';

    // REMINDER LEVEL

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_1 = '1';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_2 = '2';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_3 = '3';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_4 = '4';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_5 = '5';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_A = 'A';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_S = 'S';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_M = 'M';

    /**
     * @var string
     */
    public const REMINDER_LEVEL_LVL_I = 'I';

    // ADDRESS CHECK DIVERGENCE

    /**
     * @var string
     */
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_SURNAME = 'L';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_FIRSTNAME = 'F';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_ADDRESS = 'A';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_DATE_OF_BIRTH = 'B';

    // ADDRESS CHECK PERSONSTATUS

    /**
     * Specification:
     * - NONE: no verification of personal data carried out
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_NONE = 'NONE';

    /**
     * Specification:
     * - PPB: first name & surname unknown
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PPB = 'PPB';

    /**
     * Specification:
     * - PHB: surname known
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PHB = 'PHB';

    /**
     * Specification:
     * - PAB: first name & surname unknown
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PAB = 'PAB';

    /**
     * Specification:
     * - PKI: ambiguity in name and address
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PKI = 'PKI';

    /**
     * Specification:
     * - PNZ: cannot be delivered (any longer)
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PNZ = 'PNZ';

    /**
     * Specification:
     * - PPV: person deceased
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PPV = 'PPV';

    /**
     * Specification:
     * - PPF: postal address details incorrect
     *
     * @var string
     */
    public const ADDRESS_CHECK_PERSONSTATUS_PPF = 'PPF';

    // ADDRESS CHECK SCORE

    /**
     * @var string
     */
    public const ADDRESS_CHECK_SCORE_GREEN = 'G';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_SCORE_YELLOW = 'Y';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_SCORE_RED = 'R';

    // ADDRESS CHECK SECSTATUS

    /**
     * @var string
     */
    public const ADDRESS_CHECK_SECSTATUS_CORRECT = '10';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_SECSTATUS_CORRECTABLE = '20';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_SECSTATUS_NONE_CORRECTABLE = '30';

    // ADDRESS CHECK TYPE

    /**
     * @var string
     */
    public const ADDRESS_CHECK_TYPE_NONE = 'NO';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_TYPE_BASIC = 'BA';

    /**
     * @var string
     */
    public const ADDRESS_CHECK_TYPE_PERSON = 'PE';

    // FILE TYPE

    /**
     * @var string
     */
    public const FILE_TYPE_MANDATE = 'SEPA_MANDATE';

    // FILE FORMAT

    /**
     * @var string
     */
    public const FILE_FORMAT_PDF = 'PDF';

    // INVOICE TITLE PREFIX

    /**
     * @var string
     */
    public const INVOICE_TITLE_PREFIX_INVOICE = 'RG';

    /**
     * @var string
     */
    public const INVOICE_TITLE_PREFIX_SECURITY_INVOICE = 'RG';

    /**
     * @var string
     */
    public const INVOICE_TITLE_PREFIX_CREDIT_NOTE = 'GT';

    // PAYPAL EXPRESS CHECKOUT ACTIONS
    /**
     * @var string
     */
    public const PAYONE_EXPRESS_CHECKOUT_SET_ACTION = 'setexpresscheckout';

    /**
     * @var string
     */
    public const PAYONE_EXPRESS_CHECKOUT_GET_DETAILS_ACTION = 'getexpresscheckoutdetails';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_CASH_ON_DELIVERY_OMS = 'payoneCashOnDelivery';
}
