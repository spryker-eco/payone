<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCalculationBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToOmsBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToRefundBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToSalesBridge;

class PayoneDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_OMS = 'oms facade';
    public const FACADE_REFUND = 'refund facade';
    public const STORE_CONFIG = 'store config';
    public const FACADE_SALES = 'sales facade';
    public const FACADE_GLOSSARY = 'glossary facade';
    public const FACADE_CALCULATION = 'calculation facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return new PayoneToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[self::FACADE_REFUND] = function (Container $container) {
            return new PayoneToRefundBridge($container->getLocator()->refund()->facade());
        };

        $container[self::FACADE_SALES] = function (Container $container) {
            return new PayoneToSalesBridge($container->getLocator()->sales()->facade());
        };
        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new PayoneToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new PayoneToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }
}
