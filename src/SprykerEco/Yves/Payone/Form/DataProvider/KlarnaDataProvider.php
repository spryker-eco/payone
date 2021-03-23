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
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(QuoteTransfer $quoteTransfer, Store $store)
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->store = $store;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if ($quoteTransfer->getPayment() !== null) {
            return $quoteTransfer;
        }

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPayone(new PayonePaymentTransfer());
        $quoteTransfer->setPayment($paymentTransfer);

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
        $items = $this->quoteTransfer->getItems();

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
