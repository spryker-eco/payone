<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerEco\Yves\Payone\PayoneConfig getConfig()
 */
class IdealOnlineTransferSubForm extends OnlineTransferSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'ideal_online_transfer';

    /**
     * @var string
     */
    public const OPTION_BANK_COUNTRIES = 'ideal online transfer bank countries';

    /**
     * @var string
     */
    public const OPTION_BANK_GROUP_TYPES = 'ideal online transfer bank group types';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addBankGroupType($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addOnlineBankTransferType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ONLINE_BANK_TRANSFER_TYPE,
            HiddenType::class,
            [
                'label' => false,
                'data' => PayoneApiConstants::ONLINE_BANK_TRANSFER_TYPE_IDEAL,
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
    protected function addBankGroupType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_BANK_GROUP_TYPE,
            ChoiceType::class,
            [
                'label' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => false,
                'choices' => $options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_GROUP_TYPES],
                'constraints' => [],
            ],
        );

        return $this;
    }
}
