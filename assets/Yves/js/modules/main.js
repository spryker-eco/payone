/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $ = require('jquery');
var paymentMethod = require('./payment-method');

$(document).ready(function() {
    // Configuration for Hosted Iframe.
    // https://github.com/fjbender/simple-php-integration#build-the-form
    var config = {
        fields: {
            cardpan: {
                selector: "cardpan",
                type: "text",
            },
            cardcvc2: {
                selector: "cardcvc2",
                type: "password",
                size: "4",
                maxlength: "4",
            },
            cardexpiremonth: {
                selector: "cardexpiremonth",
                type: "select",
                size: "2",
                maxlength: "2",
                iframe: {
                    width: "50px",
                }
            },
            cardexpireyear: {
                selector: "cardexpireyear",
                type: "select",
                iframe: {
                    width: "80px",
                }
            }
        },
        defaultStyle: {
            input: "font-size: 1em;height: 2.4375rem;border-radius: 5px;border: 1px solid #cacaca;",
            select: "font-size: 1em;height: 2.4375rem;border-radius: 5px;border: 1px solid #cacaca; background-color: #fefefe;",
            iframe: {
                height: "50px",
                width: "180px"
            }
        },
        error: "errorOutput",
        language: Payone.ClientApi.Language.de
    };

    paymentMethod.init({
        formSelector: '[name="paymentForm"]',
        formId: 'payment-form',
        paymentMethodSelector: 'input[type="radio"][name="paymentForm[paymentSelection]"]',
        currentPaymentMethodSelector: 'input[type="radio"][name="paymentForm[paymentSelection]"]:checked',
        cardholderInput: '#paymentForm_payoneCreditCard_cardholder',
        clientApiConfigInput: '#paymentForm_payoneCreditCard_payone_client_api_config',
        languageInput: '#Payone_credit_card_payone_client_lang_code',
        bankAccountModeBbanInput: '#paymentForm_payoneDirectDebit_bankaccountmode_0',
        bankAccountModeIbanBicInput: '#paymentForm_payoneDirectDebit_bankaccountmode_1',
        bankAccountInput: '#paymentForm_payoneDirectDebit_bankaccount',
        bankCodeInput: '#paymentForm_payoneDirectDebit_bankcode',
        ibanInput: '#paymentForm_payoneDirectDebit_iban',
        bicInput: '#paymentForm_payoneDirectDebit_bic',
        errorDivSelector: '#errorOutput',
        hostedIframeConfig: config
    });
});