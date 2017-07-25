<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\PayonePaypalExpressCheckoutTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCheckoutInterface;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCustomerQueryBridge;
use SprykerEco\Zed\Payone\PayoneConfig;

class ExpressCheckoutOrderSaver implements ExpressCheckoutOrderSaverInterface
{

    /**
     * @const PAYMENT_PROVIDER
     */
    const PAYMENT_PROVIDER = 'Payone';

    /**
     * @var \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCheckoutInterface
     */
    protected $checkoutFacade;

    /**
     * @var \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCustomerQueryInterface
     */
    protected $customerQueryContainer;

    /**
     * @var \SprykerEco\Zed\Payone\PayoneConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCheckoutInterface $checkoutFacade
     * @param \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCustomerQueryInterface $customerQueryContainer
     * @param \SprykerEco\Zed\Payone\PayoneConfig $config
     */
    public function __construct(
        PayoneToCheckoutInterface $checkoutFacade,
        PayoneToCustomerQueryBridge $customerQueryContainer,
        PayoneConfig $config
    )
    {
        $this->checkoutFacade = $checkoutFacade;
        $this->customerQueryContainer = $customerQueryContainer;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
    )
    {
        $quoteTransfer = $this->getHydratedQuote($quoteTransfer, $details);
        return $this->checkoutFacade->placeOrder($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function getHydratedQuote(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details)
    {
        $this->updateQuoteWithPayment($quoteTransfer);
        $this->updateQuoteWithShipment($quoteTransfer);
        $this->updateQuoteWithCustomer($quoteTransfer, $details);
        $this->updateQuoteWithAddresses($quoteTransfer, $details);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function updateQuoteWithPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = new PaymentTransfer();
        $payone = new PayonePaymentTransfer();
        $paymentDetailTransfer = new PaymentDetailTransfer();
        $paymentDetailTransfer
            ->setAmount($quoteTransfer->getTotals()->getGrandTotal())
            ->setType(PayoneApiConstants::E_WALLET_TYPE_PAYPAL)
            ->setCurrency($this->config->getRequestStandardParameter()->getCurrency());
        $payone->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXTERNAL_CHECKOUT);
        $payone->setPaymentDetail($paymentDetailTransfer);

        $paymentTransfer->setPayone($payone);
        $paymentTransfer->setPaymentSelection(PayoneConfig::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT);
        $paymentTransfer->setPaymentProvider(static::PAYMENT_PROVIDER);
        $paypalExpressCheckoutPayment = new PayonePaypalExpressCheckoutTransfer();
        $paymentTransfer->setPayonePaypalExpressCheckout($paypalExpressCheckoutPayment);
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function updateQuoteWithShipment($quoteTransfer)
    {
        $shipmentTransfer = new ShipmentTransfer();

        //TODO: discuss what should be done here. What exactly shipping method to use.
        $shipmentTransfer->setMethod(
            (new ShipmentMethodTransfer())
                ->setCarrierName('Paypal')
                ->setDefaultPrice(0)
        );
        $quoteTransfer->setShipment($shipmentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function updateQuoteWithCustomer(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
    )
    {
        $customerEmail = $details->getEmail();

        $customer = $this->customerQueryContainer
            ->queryCustomerByEmail($customerEmail)->findOne();

        if (!empty($customer)) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($customer->toArray(), true);
            $quoteTransfer->setCustomer($customerTransfer);
            return $quoteTransfer;
        }
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail($customerEmail);
        $customerTransfer->setFirstName($details->getShippingFirstName());
        $customerTransfer->setLastName($details->getShippingLastName());
        $customerTransfer->setCompany($details->getShippingCompany());
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function updateQuoteWithAddresses(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
    )
    {
        $shippingAddress = new AddressTransfer();
        $shippingAddress->setFirstName($details->getShippingFirstName());
        $shippingAddress->setLastName($details->getShippingLastName());
        $shippingAddress->setCompany($details->getShippingCompany());
        $shippingAddress->setEmail($details->getEmail());
        $shippingAddress->setAddress1($details->getShippingStreet());
        $shippingAddress->setAddress2($details->getShippingAddressAdition());
        $shippingAddress->setCity($details->getShippingCity());
        $shippingAddress->setState($details->getShippingState());
        $shippingAddress->setIso2Code($details->getShippingCountry());
        $shippingAddress->setZipCode($details->getShippingZip());
        $quoteTransfer->setBillingAddress($shippingAddress);
        $quoteTransfer->setShippingAddress($shippingAddress);
        $quoteTransfer->setBillingSameAsShipping(true);

        return $quoteTransfer;
    }
}