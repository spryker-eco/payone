<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentEWalletTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EWalletSubForm extends AbstractPayoneSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'e_wallet';

    /**
     * @var string
     */
    public const FIELD_WALLET_TYPE = 'wallettype';

    /**
     * @var string
     */
    public const OPTION_WALLET_CHOICES = 'wallet_types';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::PAYONE_E_WALLET;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::PAYONE_E_WALLET;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return PayoneConstants::PROVIDER_NAME . '/' . static::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PayonePaymentEWalletTransfer::class,
        ])->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolver $resolver): void
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addWalletType($builder, $options[SubFormInterface::OPTIONS_FIELD_NAME][static::OPTION_WALLET_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addWalletType(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(
            static::FIELD_WALLET_TYPE,
            ChoiceType::class,
            [
                'label' => false,
                'required' => true,
                'choices' => $choices,
            ],
        );

        return $this;
    }
}
