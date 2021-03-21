<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Payone\Form\KlarnaSubForm;

class KlarnaDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClient
     */
    protected $quoteTransfer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    public function __construct(QuoteTransfer $quoteTransfer, Store $store)
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->store = $store;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setPayone(new PayonePaymentTransfer());
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $billingAddress = $this->quoteTransfer->getBillingAddress();
        $itemTransfers = $this->quoteTransfer->getItems();

        /** @var \Generated\Shared\Transfer\ItemTransfer $firstItem */
        $firstItem = $itemTransfers->getIterator()->current();
        $firstItemShipment = $firstItem->getShipment();

        $shippingAddress = $firstItemShipment->getShippingAddress();

        return [
            KlarnaSubForm::PAY_METHOD_CHOICES => $this->getPayMethods(),
            KlarnaSubForm::BILLING_ADDRESS_DATA => [
                'given_name' => $billingAddress->getFirstName(),
                'family_name' => $billingAddress->getLastName(),
                'email' => $quoteTransfer->getCustomer()->getEmail(),
                'street_address' => implode(' ', [$billingAddress->getAddress1(), $billingAddress->getAddress2()]),
                'postal_code' => $billingAddress->getZipCode(),
                'city' => $billingAddress->getCity(),
                'country' => $this->store->getCurrentCountry(),
                'phone' => $billingAddress->getPhone(),
            ],
            KlarnaSubForm::SHIPPING_ADDRESS_DATA => [
                'given_name' => $shippingAddress->getFirstName(),
                'family_name' => $shippingAddress->getLastName(),
                'email' => $quoteTransfer->getCustomer()->getEmail(),
                'street_address' => implode(' ', [$shippingAddress->getAddress1(), $shippingAddress->getAddress2()]),
                'postal_code' => $billingAddress->getZipCode(),
                'city' => $shippingAddress->getCity(),
                'country' => $this->store->getCurrentCountry(),
                'phone' => $shippingAddress->getPhone(),

            ],
            KlarnaSubForm::CUSTOMER_DATA => [
                'date_of_birth' => $quoteTransfer->getCustomer()->getDateOfBirth(),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPayMethods(): array
    {
        return [
            'Slice it' => 'KIS',
            'Pay later' => 'KIV',
            'Pay now' => 'KDD',
        ];
    }
}
