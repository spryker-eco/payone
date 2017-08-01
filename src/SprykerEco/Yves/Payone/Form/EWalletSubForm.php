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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EWalletSubForm extends AbstractPayoneSubForm
{

    const PAYMENT_METHOD = 'e_wallet';
    const FIELD_WALLET_TYPE = 'wallettype';

    const OPTION_WALLET_CHOICES = 'wallet_types';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::PAYONE_E_WALLET;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::PAYONE_E_WALLET;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return PayoneConstants::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'data_class' => PayonePaymentEWalletTransfer::class,
            SubFormInterface::OPTIONS_FIELD_NAME => [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            self::FIELD_WALLET_TYPE,
            'choice',
            [
                'label' => false,
                'required' => true,
                'choices' => $choices,
            ]
        );

        return $this;
    }

}
