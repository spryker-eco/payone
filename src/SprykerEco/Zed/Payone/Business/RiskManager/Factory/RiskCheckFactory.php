<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\RiskManager\Factory;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AddressCheckContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ConsumerScoreContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AddressCheckResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\ConsumerScoreResponseContainer;

class RiskCheckFactory implements RiskCheckFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function createAddressCheckContainer(): ContainerInterface
    {
        return new AddressCheckContainer();
    }

    /**
     * @return ContainerInterface
     */
    public function createConsumerScoreContainer(): ContainerInterface
    {
        return new ConsumerScoreContainer();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer
     */
    public function createAddressCheckResponseContainer(): AbstractResponseContainer
    {
        return new AddressCheckResponseContainer();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer
     */
    public function createConsumerScoreResponseContainer(): AbstractResponseContainer
    {
        return new ConsumerScoreResponseContainer();
    }
}
