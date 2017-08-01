<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacade getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class AuthorizationIsRedirectConditionPlugin extends AbstractPlugin
{

    const NAME = 'AuthorizationIsRedirectPlugin';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isAuthorizationRedirect($orderTransfer);
    }

}
