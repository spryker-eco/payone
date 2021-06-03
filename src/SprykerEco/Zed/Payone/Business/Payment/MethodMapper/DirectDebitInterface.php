<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetFileContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ManageMandateContainer;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;

interface DirectDebitInterface extends PaymentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer
     */
    public function mapBankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer): BankAccountCheckContainer;

    /**
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ManageMandateContainer
     */
    public function mapManageMandate(PayoneManageMandateTransfer $manageMandateTransfer): ManageMandateContainer;

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GetFileContainer
     */
    public function mapGetFile(PayoneGetFileTransfer $getFileTransfer): GetFileContainer;
}
