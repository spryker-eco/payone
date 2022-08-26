<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 */
class PayoneCheckoutPostSavePlugin extends AbstractPlugin implements CheckoutPostSaveInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CheckoutResponseTransfer.saveOrder.idSalesOrder` to be set.
     * - Requires `QuoteTransfer.payment.payone.fkSalesOrder` to be set.
     * - If the payment provider is 'Payone' it handles redirects and errors after order placement otherwise doesn't do anything.
     * - Executes `authorization` or `pre-authorization` API call depends on payment method.
     * - Updates `CheckoutResponseTransfer` with errors or/and redirect url accordingly to API response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFacade()->orderPostSave($quoteTransfer, $checkoutResponseTransfer);
    }
}
