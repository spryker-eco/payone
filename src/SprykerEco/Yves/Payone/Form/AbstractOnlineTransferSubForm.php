<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractOnlineTransferSubForm extends AbstractPayoneSubForm
{
    public const PAYMENT_METHOD = 'online_transfer';
    public const FIELD_IBAN = 'iban';
    public const FIELD_BIC = 'bic';
    public const FIELD_BANK_COUNTRY = 'bankcountry';
    public const FIELD_BANK_ACCOUNT = 'bankaccount';
    public const FIELD_BANK_CODE = 'bankcode';
    public const FIELD_BANK_BRANCH_CODE = 'bankbranchcode';
    public const FIELD_BANK_CHECK_DIGIT = 'bankcheckdigit';
    public const FIELD_ONLINE_BANK_TRANSFER_TYPE = 'onlinebanktransfertype';
    public const FIELD_BANK_GROUP_TYPE = 'bankgrouptype';
    public const OPTION_ONLINE_BANK_TRANSFER_TYPES = 'online bank transfer types';
    public const OPTION_BANK_COUNTRIES = '';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::PAYONE_ONLINE_TRANSFER;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return PayoneConstants::PROVIDER_NAME . '/' . static::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayonePaymentOnlinetransferTransfer::class,
        ])->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolver $resolver)
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
        $this->addOnlineBankTransferType($builder, $options)
            ->addBankCountry($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
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
     * @return $this
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
     *
     * @return $this
     */
    protected function addBankBranchCode(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_BRANCH_CODE,
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
     * @return $this
     */
    protected function addBankCheckDigit(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_CHECK_DIGIT,
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
     * @return $this
     */
    protected function addIBAN(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IBAN,
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
     * @return $this
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
     * @return $this
     */
    protected function addBIC(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BIC,
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
     * @param \Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer $data
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function checkBankAccount(PayonePaymentOnlinetransferTransfer $data, ExecutionContextInterface $context)
    {
        $quoteTransfer = $context->getRoot()->getData();
        if ($quoteTransfer->getPayment()->getPaymentSelection() != $this->getPropertyPath()) {
            return;
        }

        $bankAccountCheckTransfer = new PayoneBankAccountCheckTransfer();
        $bankAccountCheckTransfer->setBankCountry($data->getBankcountry());
        $bankAccountCheckTransfer->setBankAccount($data->getBankaccount());
        $bankAccountCheckTransfer->setBankCode($data->getBankcode());
        $bankAccountCheckTransfer->setBankBranchCode($data->getBankbranchcode());
        $bankAccountCheckTransfer->setBankCheckDigit($data->getBankcheckdigit());
        $bankAccountCheckTransfer->setIban($data->getIban());
        $bankAccountCheckTransfer->setBic($data->getBic());

        $response = $this->getClient()->bankAccountCheck($bankAccountCheckTransfer);
        if ($response->getStatus() == 'ERROR' || $response->getStatus() == 'INVALID') {
            $context->addViolation($response->getCustomerErrorMessage());
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    abstract public function addOnlineBankTransferType(FormBuilderInterface $builder, array $options);
}
