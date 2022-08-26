<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\Constraint;

use SprykerEco\Client\Payone\PayoneClientInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ManageMandate extends SymfonyConstraint
{
    /**
     * @var string
     */
    public const OPTION_PAYONE_CLIENT = 'payoneClient';

    /**
     * @var \SprykerEco\Client\Payone\PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @return \SprykerEco\Client\Payone\PayoneClientInterface
     */
    public function getPayoneClient(): PayoneClientInterface
    {
        return $this->payoneClient;
    }
}
