<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentCreditCardTransfer;
use Generated\Shared\Transfer\PayonePaymentDirectDebitTransfer;
use Generated\Shared\Transfer\PayonePaymentEWalletTransfer;
use Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use Symfony\Component\HttpFoundation\Request;

class PayoneHandler implements PayoneHandlerInterface
{
    /**
     * @var string
     */
    public const PAYMENT_PROVIDER = 'Payone';

    /**
     * @var string
     */
    public const CHECKOUT_INCLUDE_SUMMARY_PATH = 'Payone/partial/summary';

    /**
     * @var string
     */
    public const CHECKOUT_INCLUDE_SUCCESS_PATH = 'Payone/partial/success';

    /**
     * @var string
     */
    protected const PAYONE_PAYMENT_REFERENCE_PREFIX = 'TX1';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        PaymentTransfer::PAYONE_CREDIT_CARD => 'credit_card',
        PaymentTransfer::PAYONE_E_WALLET => 'e_wallet',
        PaymentTransfer::PAYONE_DIRECT_DEBIT => 'direct_debit',
        PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER => 'instant_online_transfer',
        PaymentTransfer::PAYONE_EPS_ONLINE_TRANSFER => 'eps_online_transfer',
        PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER => 'giropay_online_transfer',
        PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER => 'ideal_online_transfer',
        PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER => 'postfinance_efinance_online_transfer',
        PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER => 'postfinance_card_online_transfer',
        PaymentTransfer::PAYONE_PRZELEWY24_ONLINE_TRANSFER => 'przelewy24_online_transfer',
        PaymentTransfer::PAYONE_BANCONTACT_ONLINE_TRANSFER => 'bancontact_online_transfer',
        PaymentTransfer::PAYONE_PRE_PAYMENT => 'prepayment',
        PaymentTransfer::PAYONE_INVOICE => 'invoice',
        PaymentTransfer::PAYONE_SECURITY_INVOICE => 'securityInvoice',
        PaymentTransfer::PAYONE_CASH_ON_DELIVERY => 'cashOnDelivery',
    ];

    /**
     * @var array
     */
    protected static $payonePaymentMethodMapper = [
        PaymentTransfer::PAYONE_CREDIT_CARD => PayoneApiConstants::PAYMENT_METHOD_CREDITCARD,
        PaymentTransfer::PAYONE_E_WALLET => PayoneApiConstants::PAYMENT_METHOD_E_WALLET,
        PaymentTransfer::PAYONE_DIRECT_DEBIT => PayoneApiConstants::PAYMENT_METHOD_DIRECT_DEBIT,
        PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_EPS_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_PRZELEWY24_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_BANCONTACT_ONLINE_TRANSFER => PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER,
        PaymentTransfer::PAYONE_PRE_PAYMENT => PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT,
        PaymentTransfer::PAYONE_INVOICE => PayoneApiConstants::PAYMENT_METHOD_INVOICE,
        PaymentTransfer::PAYONE_SECURITY_INVOICE => PayoneApiConstants::PAYMENT_METHOD_SECURITY_INVOICE,
        PaymentTransfer::PAYONE_CASH_ON_DELIVERY => PayoneApiConstants::PAYMENT_METHOD_CASH_ON_DELIVERY,
        PaymentTransfer::PAYONE_KLARNA => PayoneApiConstants::PAYMENT_METHOD_KLARNA,
    ];

    /**
     * @var array
     */
    protected static $payoneGenderMapper = [
        'Mr' => 'Male',
        'Mrs' => 'Female',
    ];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $paymentSelection = $quoteTransfer->getPaymentOrFail()->getPaymentSelectionOrFail();

        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setPayonePayment($request, $quoteTransfer, $paymentSelection);
        $this->setPaymentSuccessIncludePath($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPaymentProviderAndMethod(QuoteTransfer $quoteTransfer, string $paymentSelection): void
    {
        $quoteTransfer->getPayment()
            ->setPaymentProvider(static::PAYMENT_PROVIDER)
            ->setPaymentMethod(static::$payonePaymentMethodMapper[$paymentSelection]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setPaymentSuccessIncludePath(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->requirePayment()->getPayment()->setSummaryIncludePath(static::CHECKOUT_INCLUDE_SUMMARY_PATH);
        $quoteTransfer->requirePayment()->getPayment()->setSuccessIncludePath(static::CHECKOUT_INCLUDE_SUCCESS_PATH);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPayonePayment(Request $request, QuoteTransfer $quoteTransfer, string $paymentSelection): void
    {
        $payonePaymentTransfer = $this->getPayonePaymentTransfer($quoteTransfer, $paymentSelection);

        $paymentDetailTransfer = new PaymentDetailTransfer();
        // get it from quotaTransfer
        $paymentDetailTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $paymentDetailTransfer->setCurrency($this->getCurrency());
        if ($payonePaymentTransfer instanceof PayonePaymentCreditCardTransfer && $paymentSelection === PaymentTransfer::PAYONE_CREDIT_CARD) {
            $paymentDetailTransfer->setPseudoCardPan($payonePaymentTransfer->getPseudocardpan());
        } elseif ($payonePaymentTransfer instanceof PayonePaymentEWalletTransfer && $paymentSelection === PaymentTransfer::PAYONE_E_WALLET) {
            $paymentDetailTransfer->setType($payonePaymentTransfer->getWallettype());
        } elseif ($payonePaymentTransfer instanceof PayonePaymentDirectDebitTransfer && $paymentSelection === PaymentTransfer::PAYONE_DIRECT_DEBIT) {
            $paymentDetailTransfer->setBankCountry($payonePaymentTransfer->getBankcountry());
            $paymentDetailTransfer->setBankAccount($payonePaymentTransfer->getBankaccount());
            $paymentDetailTransfer->setBankCode($payonePaymentTransfer->getBankcode());
            $paymentDetailTransfer->setBic($payonePaymentTransfer->getBic());
            $paymentDetailTransfer->setIban($payonePaymentTransfer->getIban());
            $paymentDetailTransfer->setMandateIdentification($payonePaymentTransfer->getMandateIdentification());
            $paymentDetailTransfer->setMandateText($payonePaymentTransfer->getMandateText());
        } elseif (
            $payonePaymentTransfer instanceof PayonePaymentOnlinetransferTransfer &&
            in_array($paymentSelection, [
                PaymentTransfer::PAYONE_EPS_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_PRZELEWY24_ONLINE_TRANSFER,
                PaymentTransfer::PAYONE_BANCONTACT_ONLINE_TRANSFER,
            ])
        ) {
            $paymentDetailTransfer->setType($payonePaymentTransfer->getOnlineBankTransferType());
            $paymentDetailTransfer->setBankCountry($payonePaymentTransfer->getBankCountry());
            if ($paymentSelection === PaymentTransfer::PAYONE_BANCONTACT_ONLINE_TRANSFER) {
                $paymentDetailTransfer->setBankCountry($quoteTransfer->getBillingAddress()->getIso2Code());
            }
            $paymentDetailTransfer->setBankAccount($payonePaymentTransfer->getBankAccount());
            $paymentDetailTransfer->setBankCode($payonePaymentTransfer->getBankCode());
            $paymentDetailTransfer->setBankBranchCode($payonePaymentTransfer->getBankBranchCode());
            $paymentDetailTransfer->setBankCheckDigit($payonePaymentTransfer->getBankCheckDigit());
            $paymentDetailTransfer->setBankGroupType($payonePaymentTransfer->getBankGroupType());
            $paymentDetailTransfer->setIban($payonePaymentTransfer->getIban());
            $paymentDetailTransfer->setBic($payonePaymentTransfer->getBic());
        } elseif ($paymentSelection == PaymentTransfer::PAYONE_CASH_ON_DELIVERY) {
            $shippingProvider = $quoteTransfer->getShipment()->getMethod()->getCarrierName();
            $paymentDetailTransfer->setShippingProvider($shippingProvider);
        }

        $payonePaymentTransfer = new PayonePaymentTransfer();
        $payonePaymentTransfer->setReference(uniqid(static::PAYONE_PAYMENT_REFERENCE_PREFIX));
        $payonePaymentTransfer->setPaymentDetail($paymentDetailTransfer);
        $paymentTransfer = $quoteTransfer->getPayment();
        $payonePaymentTransfer->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $paymentTransfer->setPayone($payonePaymentTransfer);
    }

    /**
     * @return string
     */
    protected function getCurrency(): string
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer|\Generated\Shared\Transfer\PayonePaymentCreditCardTransfer|\Generated\Shared\Transfer\PayonePaymentEWalletTransfer|\Generated\Shared\Transfer\PayonePaymentDirectDebitTransfer|\Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer
     */
    protected function getPayonePaymentTransfer(QuoteTransfer $quoteTransfer, string $paymentSelection)
    {
        $method = 'get' . ucfirst($paymentSelection);
        $payonePaymentTransfer = $quoteTransfer->getPayment()->$method();

        return $payonePaymentTransfer;
    }
}
