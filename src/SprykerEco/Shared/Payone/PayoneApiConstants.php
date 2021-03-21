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

    public const PROVIDER_NAME = 'payone';

    // MODE

    public const MODE_TEST = 'test';
    public const MODE_LIVE = 'live';

    // VERSIONS

    public const API_VERSION_3_8 = '3.8';
    public const API_VERSION_3_9 = '3.9';

    // INTEGRATOR

    public const INTEGRATOR_NAME_SPRYKER = 'spryker';
    public const INTEGRATOR_VERSION_3_0_0 = '3.0.0';

    // SOLUTION

    public const SOLUTION_NAME_SPRYKER = 'spryker';
    public const SOLUTION_VERSION_3_0_0 = '3.0.0';

    // PAYMENT METHODS

    // credit/debit card methods
    public const PAYMENT_METHOD_CREDITCARD = 'payment.payone.creditcard';
    public const PAYMENT_METHOD_CREDITCARD_PSEUDO = 'payment.payone.creditcard';

    // e-wallet methods
    public const PAYMENT_METHOD_E_WALLET = 'payment.payone.e_wallet';
    public const PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT = 'payment.payone.paypal_external_checkout';

    // bank account based methods
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'payment.payone.direct_debit';
    public const PAYMENT_METHOD_INVOICE = 'payment.payone.invoice';
    public const PAYMENT_METHOD_SECURITY_INVOICE = 'payment.payone.security_invoice';
    public const PAYMENT_METHOD_PREPAYMENT = 'payment.payone.prepayment';
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'payment.payone.cash_on_delivery';

    // online transfer methods
    public const PAYMENT_METHOD_ONLINE_BANK_TRANSFER = 'payment.payone.online_bank_transfer';

    // klarna payments
    public const PAYMENT_METHOD_KLARNA = 'payment.payone.klarna';
    public const PAYMENT_KLARNA_START_SESSION_ACTION = 'start_session';

    // CLEARING TYPE

    public const CLEARING_TYPE_DIRECT_DEBIT = 'elv';
    public const CLEARING_TYPE_CREDIT_CARD = 'cc';
    public const CLEARING_TYPE_PREPAYMENT = 'vor';
    public const CLEARING_TYPE_INVOICE = 'rec';
    public const CLEARING_TYPE_SECURITY_INVOICE = 'rec';
    public const CLEARING_TYPE_ONLINE_BANK_TRANSFER = 'sb';
    public const CLEARING_TYPE_CASH_ON_DELIVERY = 'cod';
    public const CLEARING_TYPE_E_WALLET = 'wlt';
    public const CLEARING_TYPE_FINANCING = 'fnc';

    //CLEARING SUBTYPE
    public const CLEARING_SUBTYPE_SECURITY_INVOICE = 'POV';

    // TXACTION

    // Defined in TransactionStatusConstants

    // WALLET TYPE

    public const E_WALLET_TYPE_PAYPAL = 'PPE';
    public const E_WALLET_TYPE_PAY_DIRECT = 'PDT';

    // USE CUSTOMER DATA

    public const USE_CUSTOMER_DATA_YES = 'yes';
    public const USE_CUSTOMER_DATA_NO = 'no';

    // STORE CARD DATA

    public const STORE_CARD_DATA_YES = 'yes';
    public const STORE_CARD_DATA_NO = 'no';

    // SHIPPING PROVIDER

    public const SHIPPING_PROVIDER_DHL = 'DHL';
    public const SHIPPING_PROVIDER_BARTOLINI = 'BRT';

    // FINANCING SETTLE ACCOUNT

    public const SETTLE_ACCOUNT_YES = 'yes';
    public const SETTLE_ACCOUNT_NO = 'no';
    public const SETTLE_ACCOUNT_AUTO = 'auto';

    // RESPONSE TYPE

    public const RESPONSE_TYPE_APPROVED = 'APPROVED';
    public const RESPONSE_TYPE_REDIRECT = 'REDIRECT';
    public const RESPONSE_TYPE_JSON = 'JSON';
    public const RESPONSE_TYPE_VALID = 'VALID';
    public const RESPONSE_TYPE_INVALID = 'INVALID';
    public const RESPONSE_TYPE_BLOCKED = 'BLOCKED';
    public const RESPONSE_TYPE_ENROLLED = 'ENROLLED';
    public const RESPONSE_TYPE_ERROR = 'ERROR';
    public const RESPONSE_TYPE_TIMEOUT = 'TIMEOUT';

    // REQUEST ENCODING

    public const REQUEST_ENCODING = 'UTF-8';

    // REQUEST TYPE

    public const REQUEST_TYPE_PREAUTHORIZATION = 'preauthorization';
    public const REQUEST_TYPE_AUTHORIZATION = 'authorization';
    public const REQUEST_TYPE_CAPTURE = 'capture';
    public const REQUEST_TYPE_REFUND = 'refund';
    public const REQUEST_TYPE_DEBIT = 'debit';
    public const REQUEST_TYPE_3DSECURE_CHECK = '3dscheck';
    public const REQUEST_TYPE_ADDRESSCHECK = 'addresscheck';
    public const REQUEST_TYPE_CONSUMERSCORE = 'consumerscore';
    public const REQUEST_TYPE_BANKACCOUNTCHECK = 'bankaccountcheck';
    public const REQUEST_TYPE_CREDITCARDCHECK = 'creditcardcheck';
    public const REQUEST_TYPE_GETINVOICE = 'getinvoice';
    public const REQUEST_TYPE_GETSECURITYINVOICE = 'getsecurityinvoice';
    public const REQUEST_TYPE_MANAGEMANDATE = 'managemandate';
    public const REQUEST_TYPE_GETFILE = 'getfile';
    public const REQUEST_TYPE_GENERICPAYMENT = 'genericpayment';

    // ONLINE BANK TRANSFER TYPE

    public const ONLINE_BANK_TRANSFER_TYPE_INSTANT_MONEY_TRANSFER = 'PNT';
    public const ONLINE_BANK_TRANSFER_TYPE_GIROPAY = 'GPY';
    public const ONLINE_BANK_TRANSFER_TYPE_EPS_ONLINE_BANK_TRANSFER = 'EPS';
    public const ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_EFINANCE = 'PFF';
    public const ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_CARD = 'PFC';
    public const ONLINE_BANK_TRANSFER_TYPE_IDEAL = 'IDL';
    public const ONLINE_BANK_TRANSFER_TYPE_PRZELEWY24 = 'P24';
    public const ONLINE_BANK_TRANSFER_TYPE_BANCONTACT = 'BCT';

    // FAILED CAUSE

    public const FAILED_CAUSE_INSUFFICIENT_FUNDS = 'soc';           // soc Insufficient funds
    public const FAILED_CAUSE_ACCOUNT_EXPIRED = 'cka';              // cka Account expired
    public const FAILED_CAUSE_UNKNOWN_ACCOUNT_NAME = 'uan';         // uan Account no. / name not idential, incorrect or savings account
    public const FAILED_CAUSE_NO_DIRECT_DEBIT = 'ndd';              // ndd No direct debit
    public const FAILED_CAUSE_RECALL = 'rcl';                       // rcl Recall
    public const FAILED_CAUSE_OBJECTION = 'obj';                    // obj Objection
    public const FAILED_CAUSE_RETURNS = 'ret';                      // ret Return
    public const FAILED_CAUSE_DEBIT_NOT_COLLECTABLE = 'nelv';       // nelv Debit cannot be collected
    public const FAILED_CAUSE_CREDITCARD_CHARGEBACK = 'cb';         // cb Credit card chargeback
    public const FAILED_CAUSE_CREDITCARD_NOT_COLLECTABLE = 'ncc';   // ncc Credit card cannot be collected

    // INVOICING ITEM TYPE

    public const INVOICING_ITEM_TYPE_GOODS = 'goods';
    public const INVOICING_ITEM_TYPE_SHIPMENT = 'shipment';
    public const INVOICING_ITEM_TYPE_HANDLING = 'handling';
    public const INVOICING_ITEM_TYPE_VOUCHER = 'voucher';

    // DELIVERY MODE

    public const DELIVERY_MODE_POST = 'M';
    public const DELIVERY_MODE_PDF = 'P';
    public const DELIVERY_MODE_NONE = 'N';

    // FINANCING TYPE

    public const FINANCING_TYPE_BSV = 'BSV'; // BILLSAFE
    public const FINANCING_TYPE_CFR = 'CFR'; // COMMERZ FINANZ

    // ECOMMERCE MODE

    public const ECOMMERCE_MODE_INTERNET = 'internet';
    public const ECOMMERCE_MODE_SECURE3D = '3dsecure';
    public const ECOMMERCE_MODE_MOTO = 'moto';

    // DEBIT TRANSACTION TYPE

    public const DEBIT_TRANSACTION_TYPE_DIRECT_DEBIT_REFUND_FEE = 'RL'; //RL: Rücklastschriftgebühr
    public const DEBIT_TRANSACTION_TYPE_DUNNING_CHARGE = 'MG'; //MG: Mahngebühren
    public const DEBIT_TRANSACTION_TYPE_DEFAULT_INTEREST = 'VZ'; //VZ: Verzugszinsen
    public const DEBIT_TRANSACTION_TYPE_SHIPPING_COSTS = 'VD'; //VD: Versandkosten
    public const DEBIT_TRANSACTION_TYPE_PAYMENT_REQUEST = 'FD'; //FD: Forderung (default bei amount >0)
    public const DEBIT_TRANSACTION_TYPE_CREDIT = 'GT'; //GT: Gutschrift (default bei amount <0)
    public const DEBIT_TRANSACTION_TYPE_RETURNS = 'RT'; //RT: Retoure

    // PERSONAL DATA

    public const PERSONAL_GENDER_MALE = 'm';
    public const PERSONAL_GENDER_FEMALE = 'f';

    // CREDITCARD TYPE

    public const CREDITCARD_TYPE_VISA = 'V';
    public const CREDITCARD_TYPE_MASTERCARD = 'M';
    public const CREDITCARD_TYPE_AMEX = 'A';
    public const CREDITCARD_TYPE_DINERS = 'D';
    public const CREDITCARD_TYPE_JCB = 'J';
    public const CREDITCARD_TYPE_MAESTRO_INTERNATIONAL = 'O';
    public const CREDITCARD_TYPE_MAESTRO_UK = 'U';
    public const CREDITCARD_TYPE_DISCOVER = 'C';
    public const CREDITCARD_TYPE_CARTE_BLEUE = 'B';

    // CONSUMER SCORE

    public const CONSUMER_SCORE_GREEN = 'G';
    public const CONSUMER_SCORE_YELLOW = 'Y';
    public const CONSUMER_SCORE_RED = 'R';

    // CONSUMER SCORE TYPE

    public const CONSUMER_SCORE_TYPE_INFOSCORE_HARD = 'IH';
    public const CONSUMER_SCORE_TYPE_INFOSCORE_ALL = 'IA';
    public const CONSUMER_SCORE_TYPE_INFOSCORE_ALL_BONI = 'IB';

    // CAPTURE MODE

    public const CAPTURE_MODE_COMPLETED = 'completed';
    public const CAPTURE_MODE_NOTCOMPLETED = 'notcompleted';

    // AVS RESULT

    public const AVS_RESULT_A = 'A';
    public const AVS_RESULT_F = 'F';
    public const AVS_RESULT_N = 'N';
    public const AVS_RESULT_U = 'U';
    public const AVS_RESULT_Z = 'Z';

    // BANK ACCOUNT CHECK TYPE

    public const BANK_ACCOUNT_CHECK_TYPE_REGULAR = '0';
    public const BANK_ACCOUNT_CHECK_TYPE_POS_BLACKLIST = '1';

    // REMINDER LEVEL

    public const REMINDER_LEVEL_LVL_1 = '1';
    public const REMINDER_LEVEL_LVL_2 = '2';
    public const REMINDER_LEVEL_LVL_3 = '3';
    public const REMINDER_LEVEL_LVL_4 = '4';
    public const REMINDER_LEVEL_LVL_5 = '5';
    public const REMINDER_LEVEL_LVL_A = 'A';
    public const REMINDER_LEVEL_LVL_S = 'S';
    public const REMINDER_LEVEL_LVL_M = 'M';
    public const REMINDER_LEVEL_LVL_I = 'I';

    // ADDRESS CHECK DIVERGENCE

    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_SURNAME = 'L';
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_FIRSTNAME = 'F';
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_ADDRESS = 'A';
    public const ADDRESS_CHECK_DIVERGENCE_DEVIANT_DATE_OF_BIRTH = 'B';

    // ADDRESS CHECK PERSONSTATUS

    public const ADDRESS_CHECK_PERSONSTATUS_NONE = 'NONE'; //NONE: no verification of personal data carried out
    public const ADDRESS_CHECK_PERSONSTATUS_PPB = 'PPB'; //PPB: first name & surname unknown
    public const ADDRESS_CHECK_PERSONSTATUS_PHB = 'PHB'; //PHB: surname known
    public const ADDRESS_CHECK_PERSONSTATUS_PAB = 'PAB'; //PAB: first name & surname unknown
    public const ADDRESS_CHECK_PERSONSTATUS_PKI = 'PKI'; //PKI: ambiguity in name and address
    public const ADDRESS_CHECK_PERSONSTATUS_PNZ = 'PNZ'; //PNZ: cannot be delivered (any longer)
    public const ADDRESS_CHECK_PERSONSTATUS_PPV = 'PPV'; //PPV: person deceased
    public const ADDRESS_CHECK_PERSONSTATUS_PPF = 'PPF'; //PPF: postal address details incorrect

    // ADDRESS CHECK SCORE

    public const ADDRESS_CHECK_SCORE_GREEN = 'G';
    public const ADDRESS_CHECK_SCORE_YELLOW = 'Y';
    public const ADDRESS_CHECK_SCORE_RED = 'R';

    // ADDRESS CHECK SECSTATUS

    public const ADDRESS_CHECK_SECSTATUS_CORRECT = '10';
    public const ADDRESS_CHECK_SECSTATUS_CORRECTABLE = '20';
    public const ADDRESS_CHECK_SECSTATUS_NONE_CORRECTABLE = '30';

    // ADDRESS CHECK TYPE

    public const ADDRESS_CHECK_TYPE_NONE = 'NO';
    public const ADDRESS_CHECK_TYPE_BASIC = 'BA';
    public const ADDRESS_CHECK_TYPE_PERSON = 'PE';

    // FILE TYPE

    public const FILE_TYPE_MANDATE = 'SEPA_MANDATE';

    // FILE FORMAT

    public const FILE_FORMAT_PDF = 'PDF';

    // INVOICE TITLE PREFIX

    public const INVOICE_TITLE_PREFIX_INVOICE = 'RG';
    public const INVOICE_TITLE_PREFIX_SECURITY_INVOICE = 'RG';
    public const INVOICE_TITLE_PREFIX_CREDIT_NOTE = 'GT';

    // PAYPAL EXPRESS CHECKOUT ACTIONS
    public const PAYONE_EXPRESS_CHECKOUT_SET_ACTION = 'setexpresscheckout';
    public const PAYONE_EXPRESS_CHECKOUT_GET_DETAILS_ACTION = 'getexpresscheckoutdetails';

    public const PAYMENT_METHOD_CASH_ON_DELIVERY_OMS = 'payoneCashOnDelivery';
}
