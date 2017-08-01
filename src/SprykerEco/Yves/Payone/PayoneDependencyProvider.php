<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class PayoneDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_PAYONE = 'payone client';

    const CLIENT_CUSTOMER = 'customer client';

    const CLIENT_CART = 'cart client';

    const CLIENT_CHECKOUT = 'checkout client';

    const CLIENT_SHIPMENT = 'shipment client';

    const QUERY_CONTAINER_CUSTOMER = 'customer query container';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[self::CLIENT_PAYONE] = function (Container $container) {
            return $container->getLocator()->payone()->client();
        };

        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return $container->getLocator()->customer()->client();
        };

        $container[self::CLIENT_CART] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };

        $container[self::CLIENT_CHECKOUT] = function (Container $container) {
            return $container->getLocator()->checkout()->client();
        };

        $container[self::CLIENT_CHECKOUT] = function (Container $container) {
            return $container->getLocator()->checkout()->client();
        };

        $container[self::CLIENT_SHIPMENT] = function (Container $container) {
            return $container->getLocator()->shipment()->client();
        };

        return $container;
    }

}
