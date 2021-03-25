<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use Symfony\Component\HttpFoundation\Request;

class PayoneHandler implements PayoneHandlerInterface
{
    public const PAYMENT_PROVIDER = 'Payone';
    public const CHECKOUT_INCLUDE_SUMMARY_PATH = 'Payone/partial/summary';
    public const CHECKOUT_INCLUDE_SUCCESS_PATH = 'Payone/partial/success';
    protected const TX_1 = 'TX1';

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
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

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
    protected function setPaymentProviderAndMethod(QuoteTransfer $quoteTransfer, $paymentSelection)
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
    protected function setPaymentSuccessIncludePath(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requirePayment()->getPayment()->setSummaryIncludePath(self::CHECKOUT_INCLUDE_SUMMARY_PATH);
        $quoteTransfer->requirePayment()->getPayment()->setSuccessIncludePath(self::CHECKOUT_INCLUDE_SUCCESS_PATH);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPayonePayment(Request $request, QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $payonePaymentTransfer = $this->getPayonePaymentTransfer($quoteTransfer, $paymentSelection);

        $paymentDetailTransfer = new PaymentDetailTransfer();
        // get it from quotaTransfer
        $paymentDetailTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $paymentDetailTransfer->setCurrency($this->getCurrency());
        if ($paymentSelection === PaymentTransfer::PAYONE_CREDIT_CARD) {
            /** @var \Generated\Shared\Transfer\PayonePaymentCreditCardTransfer $payonePaymentTransfer */
            $paymentDetailTransfer->setPseudoCardPan($payonePaymentTransfer->getPseudocardpan());
        } elseif ($paymentSelection === PaymentTransfer::PAYONE_E_WALLET) {
            /** @var \Generated\Shared\Transfer\PayonePaymentEWalletTransfer $payonePaymentTransfer */
            $paymentDetailTransfer->setType($payonePaymentTransfer->getWallettype());
        } elseif ($paymentSelection === PaymentTransfer::PAYONE_DIRECT_DEBIT) {
            /** @var \Generated\Shared\Transfer\PayonePaymentDirectDebitTransfer $payonePaymentTransfer */
            $paymentDetailTransfer->setBankCountry($payonePaymentTransfer->getBankcountry());
            $paymentDetailTransfer->setBankAccount($payonePaymentTransfer->getBankaccount());
            $paymentDetailTransfer->setBankCode($payonePaymentTransfer->getBankcode());
            $paymentDetailTransfer->setBic($payonePaymentTransfer->getBic());
            $paymentDetailTransfer->setIban($payonePaymentTransfer->getIban());
            $paymentDetailTransfer->setMandateIdentification($payonePaymentTransfer->getMandateIdentification());
            $paymentDetailTransfer->setMandateText($payonePaymentTransfer->getMandateText());
        } elseif (
            $paymentSelection === PaymentTransfer::PAYONE_EPS_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_PRZELEWY24_ONLINE_TRANSFER
            || $paymentSelection === PaymentTransfer::PAYONE_BANCONTACT_ONLINE_TRANSFER
        ) {
            /** @var \Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer $payonePaymentTransfer */
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
        } elseif ($paymentSelection == PaymentTransfer::PAYONE_KLARNA) {
            $paymentDetailTransfer->setPayMethod($payonePaymentTransfer->getPayMethod());
            $paymentDetailTransfer->setTokenList($payonePaymentTransfer->getPayMethodTokens());
        }

        $payone = new PayonePaymentTransfer();
        $payone->setReference(uniqid(self::TX_1));
        $payone->setPaymentDetail($paymentDetailTransfer);
        $paymentTransfer = $quoteTransfer->getPayment();
        $payone->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $paymentTransfer->setPayone($payone);
    }

    /**
     * @return string
     */
    protected function getCurrency()
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\PayonePaymentTransfer
     */
    protected function getPayonePaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $method = 'get' . ucfirst($paymentSelection);
        $payonePaymentTransfer = $quoteTransfer->getPayment()->$method();

        return $payonePaymentTransfer;
    }
}
