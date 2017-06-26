/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $ = require('jquery');

function init(config) {
    var $form = $(config.formSelector);
    $form.submit(function(ev) {
        window.processPayoneResponse = function(response) {
            console.log(response);
            if (response.status == 'VALID') {
                $form.find(config.cardpanInput).val('');
                $form.find(config.cardcvc2Input).val('');
                $form.find(config.pseudocardpanInput).val(response.pseudocardpan);
                $form.unbind('submit').submit();
            }
            else {
                alert(response.customermessage);
            }
        };
        if ($(config.currentPaymentMethodSelector).val() === 'payoneCreditCard') {
            ev.preventDefault();
            var clientApiConfig = JSON.parse($form.find(config.clientApiConfigInput).val());
            var data = $.extend({}, clientApiConfig, {
                cardcvc2 : $form.find(config.cardcvc2Input).val() || 'wrong_value_empty_cvc2',
                cardexpiremonth : $form.find(config.cardexpiremonthInput).val(),
                cardexpireyear : $form.find(config.cardexpireyearInput).val(),
                cardholder : $form.find(config.cardholderInput).val(),
                cardpan : $form.find(config.cardpanInput).val(),
                cardtype : $form.find(config.cardtypeInput).val(),
                language: $form.find(config.languageInput).val().substr(0,2),
            });

            var options = {
                return_type : 'json',
                callback_function_name : 'processPayoneResponse'
            };

            var request = new PayoneRequest(data, options);
            request.checkAndStore();
        }
    });

    var $bankAccountModeBban = $form.find(config.bankAccountModeBbanInput);
    $bankAccountModeBban.change(function() {
        $form.find(config.bankAccountInput).prop('disabled', false);
        $form.find(config.bankCodeInput).prop('disabled', false);
        $form.find(config.ibanInput).prop('disabled', true);
        $form.find(config.bicInput).prop('disabled', true);
    });

    var $bankAccountModeIbanBic = $form.find(config.bankAccountModeIbanBicInput);
    $bankAccountModeIbanBic.change(function() {
        $form.find(config.bankAccountInput).prop('disabled', true);
        $form.find(config.bankCodeInput).prop('disabled', true);
        $form.find(config.ibanInput).prop('disabled', false);
        $form.find(config.bicInput).prop('disabled', false);
    });

    if ($bankAccountModeBban.is(':checked')) {
        $bankAccountModeBban.change();
    } else {
        $bankAccountModeIbanBic.prop('checked', true);
        $bankAccountModeIbanBic.change();
    }
}

module.exports = {
    init: init
};