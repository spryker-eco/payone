<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Model;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;

interface ParametersReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    public function getRequestStandardParameter(): PayoneStandardParameterTransfer;
}
