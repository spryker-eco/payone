<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface PayoneToCustomerInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();

    /**
     * @return bool
     */
    public function isLoggedIn();

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByEmail(CustomerTransfer $customerTransfer);
}
