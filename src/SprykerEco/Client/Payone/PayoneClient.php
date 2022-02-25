<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone;

use Generated\Shared\Transfer\AddressCheckResponseTransfer;
use Generated\Shared\Transfer\ConsumerScoreResponseTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCancelRedirectTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer;
use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\PayonePersonalDataTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @api
 *
 * @method \SprykerEco\Client\Payone\PayoneFactory getFactory()
 */
class PayoneClient extends AbstractClient implements PayoneClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \SprykerEco\Client\Payone\ClientApi\Request\CreditCardCheckContainerInterface
     */
    public function getCreditCardCheckRequest()
    {
        $defaults = [];

        return $this->getFactory()->createCreditCardCheckCall($defaults)->mapCreditCardCheckData();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $payoneTransactionStatusUpdateTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer
     */
    public function updateStatus(PayoneTransactionStatusUpdateTransfer $payoneTransactionStatusUpdateTransfer)
    {
        return $this->getFactory()->createZedStub()->updateStatus($payoneTransactionStatusUpdateTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $payoneGetFileTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetFileTransfer
     */
    public function getFile(PayoneGetFileTransfer $payoneGetFileTransfer)
    {
        return $this->getFactory()->createZedStub()->getFile($payoneGetFileTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCancelRedirectTransfer $payoneCancelRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneCancelRedirectTransfer
     */
    public function cancelRedirect(PayoneCancelRedirectTransfer $payoneCancelRedirectTransfer)
    {
        return $this->getFactory()->createZedStub()->cancelRedirect($payoneCancelRedirectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $payoneBankAccountCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer
     */
    public function bankAccountCheck(PayoneBankAccountCheckTransfer $payoneBankAccountCheckTransfer)
    {
        return $this->getFactory()->createZedStub()->bankAccountCheck($payoneBankAccountCheckTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneManageMandateTransfer
     */
    public function manageMandate(QuoteTransfer $quoteTransfer)
    {
        $manageMandateTransfer = new PayoneManageMandateTransfer();
        $manageMandateTransfer->setBankCountry($quoteTransfer->getPayment()->getPayoneDirectDebit()->getBankcountry());
        $manageMandateTransfer->setBankAccount($quoteTransfer->getPayment()->getPayoneDirectDebit()->getBankaccount());
        $manageMandateTransfer->setBankCode($quoteTransfer->getPayment()->getPayoneDirectDebit()->getBankcode());
        $manageMandateTransfer->setIban($quoteTransfer->getPayment()->getPayoneDirectDebit()->getIban());
        $manageMandateTransfer->setBic($quoteTransfer->getPayment()->getPayoneDirectDebit()->getBic());
        $payonePersonalDataTransfer = new PayonePersonalDataTransfer();
        $customer = $quoteTransfer->getCustomer();
        $billingAddress = $quoteTransfer->getBillingAddress();
        $payonePersonalDataTransfer->setCustomerId((string)$customer->getIdCustomer());
        $payonePersonalDataTransfer->setLastName($billingAddress->getLastName());
        $payonePersonalDataTransfer->setFirstName($billingAddress->getFirstName());
        $payonePersonalDataTransfer->setCompany($billingAddress->getCompany());
        $payonePersonalDataTransfer->setCountry($billingAddress->getIso2Code());
        $payonePersonalDataTransfer->setCity($billingAddress->getCity());
        $payonePersonalDataTransfer->setStreet($billingAddress->getAddress1());
        $payonePersonalDataTransfer->setZip($billingAddress->getZipCode());
        $payonePersonalDataTransfer->setEmail($billingAddress->getEmail() ?? $customer->getEmail());
        $manageMandateTransfer->setPersonalData($payonePersonalDataTransfer);

        return $this->getFactory()->createZedStub()->manageMandate($manageMandateTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer $payoneGetPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer
     */
    public function getPaymentDetail(PayoneGetPaymentDetailTransfer $payoneGetPaymentDetailTransfer)
    {
        return $this->getFactory()->createZedStub()->getPaymentDetail($payoneGetPaymentDetailTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $payoneGetInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetInvoiceTransfer
     */
    public function getInvoice(PayoneGetInvoiceTransfer $payoneGetInvoiceTransfer)
    {
        return $this->getFactory()->createZedStub()->getInvoice($payoneGetInvoiceTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $payoneInitPaypalExpressCheckoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function initPaypalExpressCheckout(
        PayoneInitPaypalExpressCheckoutRequestTransfer $payoneInitPaypalExpressCheckoutRequestTransfer
    ) {
        return $this->getFactory()->createZedStub()->initPaypalExpressCheckout($payoneInitPaypalExpressCheckoutRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function getPaypalExpressCheckoutDetails(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->getPaypalExpressCheckoutDetails($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequest(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->sendAddressCheckRequest($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequest(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->sendConsumerScoreRequest($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneKlarnaStartSessionResponseTransfer
     */
    public function sendKlarnaStartSessionRequest(
        PayoneKlarnaStartSessionRequestTransfer $payoneKlarnaStartSessionRequestTransfer
    ) {
        return $this->getFactory()->createZedStub()->sendKlarnaStartSessionRequest($payoneKlarnaStartSessionRequestTransfer);
    }
}
