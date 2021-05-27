<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface;
use SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

interface BasePaymentMethodMapperInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface $sequenceNumberProvider
     *
     * @return void
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider): void;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Key\HmacGeneratorInterface $urlHmacGenerator
     *
     * @return void
     */
    public function setUrlHmacGenerator(HmacGeneratorInterface $urlHmacGenerator): void;

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     *
     * @return void
     */
    public function setStandardParameter(PayoneStandardParameterTransfer $standardParameter): void;
}
