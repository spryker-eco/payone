<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler\ExpressCheckout;

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
use Spryker\Client\Calculation\CalculationClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\Shipment\ShipmentClientInterface;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneConstants;

class QuoteHydrator implements QuoteHydratorInterface
{
    /**
     * @const string CARRIER_NAME
     */
    const CARRIER_NAME = 'Paypal';

    /**
     * @const int DEFAULT_SHIPPING_PRICE
     */
    const DEFAULT_SHIPPING_PRICE = 0;

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\Shipment\ShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @var \Spryker\Client\Calculation\CalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @param \Spryker\Client\Shipment\ShipmentClientInterface $shipmentClient
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\Calculation\CalculationClientInterface $calculationClient
     */
    public function __construct(
        ShipmentClientInterface $shipmentClient,
        CustomerClientInterface $customerClient,
        CalculationClientInterface $calculationClient
    ) {
        $this->shipmentClient = $shipmentClient;
        $this->customerClient = $customerClient;
        $this->calculationClient = $calculationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function getHydratedQuote(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
    ) {
        $quoteTransfer = $this->hydrateQuoteWithPayment($quoteTransfer);
        $quoteTransfer = $this->hydrateQuoteWithShipment($quoteTransfer);
        $quoteTransfer = $this->hydrateQuoteWithCustomer($quoteTransfer, $details);
        $quoteTransfer = $this->hydrateQuoteWithAddresses($quoteTransfer, $details);
        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function hydrateQuoteWithPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = new PaymentTransfer();
        $payone = new PayonePaymentTransfer();
        $paymentDetailTransfer = new PaymentDetailTransfer();
        $paymentDetailTransfer
            ->setAmount($quoteTransfer->getTotals()->getGrandTotal())
            ->setType(PayoneApiConstants::E_WALLET_TYPE_PAYPAL)
            ->setCurrency(Store::getInstance()->getCurrencyIsoCode())
            ->setWorkOrderId(
                $quoteTransfer->getPayment()->getPayonePaypalExpressCheckout()->getWorkOrderId()
            );
        $payone->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT);
        $payone->setPaymentDetail($paymentDetailTransfer);

        $paymentTransfer->setPayone($payone);
        $paymentTransfer->setPaymentSelection(PayoneConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT_STATE_MACHINE);
        $paymentTransfer->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL_EXPRESS_CHECKOUT);
        $paymentTransfer->setPaymentProvider(PayoneConstants::PROVIDER_NAME);
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
    protected function hydrateQuoteWithShipment($quoteTransfer)
    {
        $shipmentTransfer = new ShipmentTransfer();

        $methods = $this->shipmentClient->getAvailableMethods($quoteTransfer)->getMethods();

        if ($shippingMethod = reset($methods)) {
            $shippingMethod->setStoreCurrencyPrice(static::DEFAULT_SHIPPING_PRICE);
            $shipmentTransfer->setMethod($shippingMethod);
            $shipmentTransfer->setShipmentSelection($shippingMethod->getIdShipmentMethod());
            $quoteTransfer->setShipment($shipmentTransfer);

            return $quoteTransfer;
        }

        $shipmentTransfer->setMethod(
            (new ShipmentMethodTransfer())
                ->setCarrierName(static::CARRIER_NAME)
                ->setStoreCurrencyPrice(static::DEFAULT_SHIPPING_PRICE)
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
    protected function hydrateQuoteWithCustomer(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
    ) {

        $customerEmail = $details->getEmail();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail($customerEmail);
        $customerTransfer = $this->customerClient->getCustomerByEmail($customerTransfer);
        if (!$this->customerClient->isLoggedIn()) {
            $quoteTransfer->setIsGuestExpressCheckout(true);
        }

        if (!empty($customerTransfer->getFirstName())) {
            $quoteTransfer->setCustomer($customerTransfer);
            return $quoteTransfer;
        }

        $customerTransfer->setFirstName($details->getShippingFirstName());
        $customerTransfer->setLastName($details->getShippingLastName());
        $customerTransfer->setCompany($details->getShippingCompany());
        $customerTransfer->setEmail($customerEmail);
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected function hydrateQuoteWithAddresses(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
    ) {

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
