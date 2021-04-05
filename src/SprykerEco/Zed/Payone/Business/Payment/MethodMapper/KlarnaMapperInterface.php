<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;

interface KlarnaMapperInterface extends PaymentMethodMapperInterface
{
    /**
     * {@inheritDoc}
     * - Maps data for Klarna start session request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneKlarnaStartSessionRequestTransfer $klarnaStartSessionRequestTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer
     */
    public function mapPaymentToStartSession(PayoneKlarnaStartSessionRequestTransfer $klarnaStartSessionRequestTransfer): ContainerInterface;
}
