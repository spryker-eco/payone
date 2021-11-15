<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\SubFormsCreator;

interface SubFormsCreatorInterface
{
    /**
     * @return array<\Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface>
     */
    public function createPaymentMethodsSubForms(): array;
}
