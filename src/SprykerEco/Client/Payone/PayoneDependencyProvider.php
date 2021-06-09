<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class PayoneDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_ZED = 'service zed';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container->set(static::SERVICE_ZED, function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        });

        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }
}
