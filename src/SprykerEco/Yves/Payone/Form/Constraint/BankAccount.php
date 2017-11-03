<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class BankAccount extends SymfonyConstraint
{
    const OPTION_PAYONE_CLIENT = 'payoneClient';

    /**
     * @var \SprykerEco\Client\Payone\PayoneClient
     */
    protected $payoneClient;

    /**
     * @return \SprykerEco\Client\Payone\PayoneClient
     */
    public function getPayoneClient()
    {
        return $this->payoneClient;
    }
}
