<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCheckoutBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCountryBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToCustomerQueryBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToOmsBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToRefundBridge;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToSalesBridge;

class PayoneDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'oms facade';
    const FACADE_REFUND = 'refund facade';
    const STORE_CONFIG = 'store config';
    const FACADE_SALES = 'sales facade';
    const FACADE_GLOSSARY = 'glossary facade';

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
