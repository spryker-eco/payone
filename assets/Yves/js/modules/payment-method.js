/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $ = require('jquery');

function init(config) {
    var $form = $(config.formSelector);

    initHostedIframe(config);

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

function initHostedIframe(config) {
    document.paymentform = document.getElementById(config.formId);
    var $form = $(config.formSelector);
    var clientApiConfig = JSON.parse($form.find(config.clientApiConfigInput).val());
    var language = $form.find(config.languageInput).val().substr(0,2);
    config.hostedIframeConfig.language = Payone.ClientApi.Language[language];

    var iframes = new Payone.ClientApi.HostedIFrames(config.hostedIframeConfig, clientApiConfig);
    iframes.setCardType("V");

    document.getElementById('cardtype').onchange = function () {
        iframes.setCardType(this.value);              // on change: set new type of credit card to process
    };

    $form.find('[type="submit"]').click(function() {
        if ($(config.currentPaymentMethodSelector).val() === 'payoneCreditCard') {
            check(iframes);
            return false;
        }
    });
}

function check(iframes) {
    //Fix to make payone remote script work correctly. It tries to remove some node but can't find it.
    document.getElementsByTagName("body")[0].appendChild(document.createElement("p"));
    window.PayoneGlobals.options.payoneScript = document.getElementsByTagName("body")[0].lastChild;

    // Payone script works with such global array member.
    // Since our function checkCallback is inside the module, it is absent in "window".
    // Set it explicitly.
    window['checkCallback'] = checkCallback;

    if (iframes.isComplete()) {
        iframes.creditCardCheck('checkCallback');
    } else {
        console.debug("The form is not complete");
    }
}

function checkCallback(response) {
    console.debug(response);
    if (response.status === "VALID") {
        document.getElementById("pseudocardpan").value = response.pseudocardpan;
        document.paymentform.submit();
    }
}

module.exports = {
    init: init
};