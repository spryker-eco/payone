<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Form\Constraint\BankAccount;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Client\Payone\PayoneClientInterface getClient()
 */
abstract class AbstractPayoneSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    /**
     * @var string
     */
    public const PAYMENT_PROVIDER = PayoneConstants::PROVIDER_NAME;

    /**
     * @var string
     */
    public const FIELD_PAYMENT_METHOD = 'paymentMethod';

    /**
     * @var string
     */
    public const FIELD_PAYONE_CREDENTIALS_MID = 'payone_mid';

    /**
     * @var string
     */
    public const FIELD_PAYONE_CREDENTIALS_AID = 'payone_aid';

    /**
     * @var string
     */
    public const FIELD_PAYONE_CREDENTIALS_PORTAL_ID = 'payone_portal_id';

    /**
     * @var string
     */
    public const FIELD_PAYONE_HASH = 'payone_hash';

    /**
     * @var string
     */
    public const FIELD_CLIENT_API_CONFIG = 'payone_client_api_config';

    /**
     * @var string
     */
    public const FIELD_CLIENT_LANG_CODE = 'payone_client_lang_code';

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return static::PAYMENT_PROVIDER;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addHiddenInputs(FormBuilderInterface $builder)
    {
        $formData = $this->getClient()->getCreditCardCheckRequest();
        $builder->add(
            static::FIELD_CLIENT_API_CONFIG,
            HiddenType::class,
            [
                'label' => false,
                'data' => $formData->toJson(),
            ],
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(): Constraint
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\Constraint\BankAccount
     */
    protected function createBankAccountConstraint(): BankAccount
    {
        return new BankAccount([BankAccount::OPTION_PAYONE_CLIENT => $this->getClient()]);
    }
}
