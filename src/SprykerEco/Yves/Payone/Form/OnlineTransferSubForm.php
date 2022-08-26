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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class OnlineTransferSubForm extends AbstractPayoneSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'online_transfer';

    /**
     * @var string
     */
    public const FIELD_IBAN = 'iban';

    /**
     * @var string
     */
    public const FIELD_BIC = 'bic';

    /**
     * @var string
     */
    public const FIELD_BANK_COUNTRY = 'bankcountry';

    /**
     * @var string
     */
    public const FIELD_BANK_ACCOUNT = 'bankaccount';

    /**
     * @var string
     */
    public const FIELD_BANK_CODE = 'bankcode';

    /**
     * @var string
     */
    public const FIELD_BANK_BRANCH_CODE = 'bankbranchcode';

    /**
     * @var string
     */
    public const FIELD_BANK_CHECK_DIGIT = 'bankcheckdigit';

    /**
     * @var string
     */
    public const FIELD_ONLINE_BANK_TRANSFER_TYPE = 'onlinebanktransfertype';

    /**
     * @var string
     */
    public const FIELD_BANK_GROUP_TYPE = 'bankgrouptype';

    /**
     * @var string
     */
    public const OPTION_BANK_GROUP_TYPES = 'online bank transfer types';

    /**
     * @var string
     */
    public const OPTION_BANK_COUNTRIES = '';

    /**
     * @var string
     */
    protected const BANK_ACCOUNT_UNKNOWN_ERROR_MESSAGE = 'payone.bank_account.unknown_error';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    abstract public function addOnlineBankTransferType(FormBuilderInterface $builder, array $options);

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::PAYONE_ONLINE_TRANSFER;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PayonePaymentOnlinetransferTransfer::class,
            'constraints' => [
                // Add Callback constraint for bank account check in ancestor classes
                // new Callback(['methods' => [[$this, 'checkBankAccount']]])
            ],
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
            ],
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
            ],
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
            ],
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
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIban(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IBAN,
            TextType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                ],
            ],
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
                ],
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
                    'choices' => array_flip($options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_COUNTRIES]),
                    'constraints' => [
                    ],
                ],
            );
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBic(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BIC,
            TextType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePaymentOnlinetransferTransfer $data
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function checkBankAccount(PayonePaymentOnlinetransferTransfer $data, ExecutionContextInterface $context): void
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
            $context->addViolation($response->getCustomerErrorMessage() ?? static::BANK_ACCOUNT_UNKNOWN_ERROR_MESSAGE);
        }
    }
}
