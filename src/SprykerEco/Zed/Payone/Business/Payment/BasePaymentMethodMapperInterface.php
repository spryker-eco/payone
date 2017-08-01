<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

interface BasePaymentMethodMapperInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     *
     * @return void
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider);

    /**
     * @param \SprykerEco\Zed\Payone\Business\Key\UrlHmacGenerator $urlHmacGenerator
     *
     * @return void
     */
    public function setUrlHmacGenerator(UrlHmacGenerator $urlHmacGenerator);

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterTransfer $standardParameter);

}
