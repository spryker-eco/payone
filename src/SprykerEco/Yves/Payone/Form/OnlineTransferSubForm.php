<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\ExecutionContextInterface;

abstract class OnlineTransferSubForm extends AbstractPayoneSubForm
{
    const PAYMENT_METHOD = 'online_transfer';
    const FIELD_IBAN = 'iban';
    const FIELD_BIC = 'bic';
    const FIELD_BANK_COUNTRY = 'bankcountry';
    const FIELD_BANK_ACCOUNT = 'bankaccount';
    const FIELD_BANK_CODE = 'bankcode';
    const FIELD_BANK_BRANCH_CODE = 'bankbranchcode';
    const FIELD_BANK_CHECK_DIGIT = 'bankcheckdigit';
    const FIELD_ONLINE_BANK_TRANSFER_TYPE = 'onlinebanktransfertype';
    const FIELD_BANK_GROUP_TYPE = 'bankgrouptype';
    const OPTION_ONLINE_BANK_TRANSFER_TYPES = 'online bank transfer types';
    const OPTION_BANK_COUNTRIES = '';

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
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'data_class' => PayonePaymentOnlinetransferTransfer::class,
            'constraints' => [
                // Add Callback constraint for bank account check in ancestor classes
                // new Callback(['methods' => [[$this, 'checkBankAccount']]])
            ],
        ])->setRequired(static::OPTIONS_FIELD_NAME);
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addBankAccount(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_ACCOUNT,
            'text',
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addBankCode(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_CODE,
            'text',
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addBankBranchCode(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_BRANCH_CODE,
            'text',
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addBankCheckDigit(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BANK_CHECK_DIGIT,
            'text',
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addIban(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IBAN,
            'text',
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addBankCountry(FormBuilderInterface $builder, array $options)
    {
        if (count($options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_COUNTRIES]) == 1) {
            $builder->add(
                static::FIELD_BANK_COUNTRY,
                'hidden',
                [
                    'label' => false,
                    'data' => array_keys($options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_COUNTRIES])[0],
                ]
            );
        } else {
            $builder->add(
                static::FIELD_BANK_COUNTRY,
                'choice',
                [
                    'label' => false,
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                    'empty_value' => false,
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    protected function addBic(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BIC,
            'text',
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
     * @param \Symfony\Component\Validator\ExecutionContextInterface $context
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
     * @return \SprykerEco\Yves\Payone\Form\OnlineTransferSubForm
     */
    abstract public function addOnlineBankTransferType(FormBuilderInterface $builder, array $options);
}
