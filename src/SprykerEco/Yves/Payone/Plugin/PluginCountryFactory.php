<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Plugin\SubFormsCreator\AtSubFormsCreator;
use SprykerEco\Yves\Payone\Plugin\SubFormsCreator\ChSubFormsCreator;
use SprykerEco\Yves\Payone\Plugin\SubFormsCreator\DefaultSubFormsCreator;
use SprykerEco\Yves\Payone\Plugin\SubFormsCreator\DeSubFormsCreator;
use SprykerEco\Yves\Payone\Plugin\SubFormsCreator\NlSubFormsCreator;
use SprykerEco\Yves\Payone\Plugin\SubFormsCreator\SubFormsCreatorInterface;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PluginCountryFactory extends AbstractPlugin
{
    /**
     * @var string
     */
    public const DEFAULT_COUNTRY = 'default';

    /**
     * @var array
     */
    protected $subFormsCreators = [];

    public function __construct()
    {
        $this->subFormsCreators = [
            PayoneConstants::COUNTRY_AT => function () {
                return new AtSubFormsCreator();
            },
            PayoneConstants::COUNTRY_NL => function () {
                return new NlSubFormsCreator();
            },
            PayoneConstants::COUNTRY_DE => function () {
                return new DeSubFormsCreator();
            },
            PayoneConstants::COUNTRY_CH => function () {
                return new ChSubFormsCreator();
            },
            static::DEFAULT_COUNTRY => function () {
                return new DefaultSubFormsCreator();
            },
        ];
    }

    /**
     * @param string $countryIso2Code
     *
     * @return \SprykerEco\Yves\Payone\Plugin\SubFormsCreator\SubFormsCreatorInterface
     */
    public function createSubFormsCreator(string $countryIso2Code): SubFormsCreatorInterface
    {
        if (isset($this->subFormsCreators[$countryIso2Code])) {
            $subFormsCreator = $this->subFormsCreators[$countryIso2Code]();
        } else {
            $subFormsCreator = $this->subFormsCreators[static::DEFAULT_COUNTRY]();
        }

        return $subFormsCreator;
    }
}
