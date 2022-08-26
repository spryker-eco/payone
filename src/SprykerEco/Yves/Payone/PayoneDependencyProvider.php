<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToCalculationBridge;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToCartBridge;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToCustomerBridge;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToQuoteClientBridge;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToShipmentBridge;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientBridge;

/**
 * @method \SprykerEco\Yves\Payone\PayoneConfig getConfig()
 */
class PayoneDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PAYONE = 'payone client';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'customer client';

    /**
     * @var string
     */
    public const CLIENT_CART = 'cart client';

    /**
     * @var string
     */
    public const CLIENT_SHIPMENT = 'shipment client';

    /**
     * @var string
     */
    public const CLIENT_CALCULATION = 'calculation client';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container->set(static::CLIENT_PAYONE, function (Container $container) {
            return $container->getLocator()->payone()->client();
        });

        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new PayoneToCustomerBridge($container->getLocator()->customer()->client());
        });

        $container->set(static::CLIENT_CART, function (Container $container) {
            return new PayoneToCartBridge($container->getLocator()->cart()->client());
        });

        $container->set(static::CLIENT_SHIPMENT, function (Container $container) {
            return new PayoneToShipmentBridge($container->getLocator()->shipment()->client());
        });

        $container->set(static::CLIENT_CALCULATION, function (Container $container) {
            return new PayoneToCalculationBridge($container->getLocator()->calculation()->client());
        });

        $container = $this->addQuoteClient($container);
        $container = $this->addClientStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new PayoneToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addClientStore(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new PayoneToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }
}
