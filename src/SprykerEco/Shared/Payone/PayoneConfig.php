<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PayoneConfig extends AbstractBundleConfig
{
    public const PAYONE_BANCONTACT_AVAILABLE_COUNTRIES = [
        'BE' => 'Belgium',
    ];
}
