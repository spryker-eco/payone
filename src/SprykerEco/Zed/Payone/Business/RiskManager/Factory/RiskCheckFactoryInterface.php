<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\RiskManager\Factory;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;

interface RiskCheckFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function createAddressCheckContainer(): ContainerInterface;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function createConsumerScoreContainer(): ContainerInterface;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer
     */
    public function createAddressCheckResponseContainer(): AbstractResponseContainer;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer
     */
    public function createConsumerScoreResponseContainer(): AbstractResponseContainer;
}
