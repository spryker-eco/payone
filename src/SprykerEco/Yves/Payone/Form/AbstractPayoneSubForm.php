<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Form\Constraint\BankAccount;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractPayoneSubForm extends AbstractSubFormType implements SubFormInterface
{

    const PAYMENT_PROVIDER = PayoneConstants::PROVIDER_NAME;

    const FIELD_PAYMENT_METHOD = 'paymentMethod';
    const FIELD_PAYONE_CREDENTIALS_MID = 'payone_mid';
    const FIELD_PAYONE_CREDENTIALS_AID = 'payone_aid';
    const FIELD_PAYONE_CREDENTIALS_PORTAL_ID = 'payone_portal_id';
    const FIELD_PAYONE_HASH = 'payone_hash';
    const FIELD_CLIENT_API_CONFIG = 'payone_client_api_config';
    const FIELD_CLIENT_LANG_CODE = 'payone_client_lang_code';

    /**
     * @var \Spryker\Client\Payolution\PayolutionClientInterface
     */
    protected $payoneClient;

    /**
     * @param \SprykerEco\Client\Payone\PayoneClientInterface $payoneClient
     */
    public function __construct(PayoneClientInterface $payoneClient)
    {
        $this->payoneClient = $payoneClient;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addHiddenInputs(FormBuilderInterface $builder)
    {
        $formData = $this->payoneClient->getCreditCardCheckRequest();
        $builder->add(
            self::FIELD_CLIENT_API_CONFIG,
            'hidden',
            [
                'label' => false,
                'data' => $formData->toJson(),
            ]
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint()
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }

    protected function createBankAccountConstraint()
    {
        return new BankAccount([BankAccount::OPTION_PAYONE_CLIENT => $this->payoneClient]);
    }

}
