<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentDirectDebitTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Yves\Payone\Form\Constraint\ManageMandate;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DirectDebitSubForm extends AbstractPayoneSubForm
{
    const PAYMENT_METHOD = 'direct_debit';
    const FIELD_IBAN = 'iban';
    const FIELD_BIC = 'bic';
    const FIELD_BANK_COUNTRY = 'bankcountry';
    const FIELD_BANK_ACCOUNT = 'bankaccount';
    const FIELD_BANK_ACCOUNT_MODE = 'bankaccountmode';
    const FIELD_BANK_CODE = 'bankcode';
    const OPTION_BANK_COUNTRIES = 'direct debit bank countries';
    const OPTION_BANK_ACCOUNT_MODE = 'direct debit bank account mode';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::PAYONE_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::PAYONE_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return PayoneConstants::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayonePaymentDirectDebitTransfer::class,
        ])->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIBAN($builder)
            ->addBIC($builder);

        if (Store::getInstance()->getCurrentCountry() === 'DE') {
            $this->addBankAccount($builder)
                ->addBankCode($builder)
                ->addBankCountry($builder, $options)
                ->addModeSwitch($builder, $options);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    protected function addModeSwitch(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_BANK_ACCOUNT_MODE,
            ChoiceType::class,
            [
                'label' => false,
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'choices' => $options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_ACCOUNT_MODE],
                'constraints' => [
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    protected function addBankAccount(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_ACCOUNT,
            TextType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    protected function addBankCode(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_CODE,
            TextType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    protected function addBankCountry(FormBuilderInterface $builder, array $options)
    {
        if (count($options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_COUNTRIES]) == 1) {
            $builder->add(
                static::FIELD_BANK_COUNTRY,
                HiddenType::class,
                [
                    'label' => false,
                    'data' => array_keys($options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_COUNTRIES])[0],
                ]
            );
        } else {
            $builder->add(
                static::FIELD_BANK_COUNTRY,
                ChoiceType::class,
                [
                    'label' => false,
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => false,
                    'choices' => $options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_COUNTRIES],
                    'constraints' => [
                    ],
                ]
            );
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    protected function addIBAN(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_IBAN,
            TextType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerEco\Yves\Payone\Form\DirectDebitSubForm
     */
    protected function addBIC(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_BIC,
            TextType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                    $this->createManageMandateConstraint(),
                ],
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

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createManageMandateConstraint()
    {
        return new ManageMandate(['payoneClient' => $this->getClient(), 'groups' => $this->getPropertyPath()]);
    }
}
