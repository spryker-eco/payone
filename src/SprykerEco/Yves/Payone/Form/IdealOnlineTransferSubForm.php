<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use Symfony\Component\Form\FormBuilderInterface;

class IdealOnlineTransferSubForm extends OnlineTransferSubForm
{
    const PAYMENT_METHOD = 'ideal_online_transfer';
    const OPTION_BANK_COUNTRIES = 'ideal online transfer bank countries';
    const OPTION_BANK_GROUP_TYPES = 'ideal online transfer bank group types';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->addBankGroupType($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Payone\Form\IdealOnlineTransferSubForm
     */
    public function addOnlineBankTransferType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ONLINE_BANK_TRANSFER_TYPE,
            'hidden',
            [
                'label' => false,
                'data' => PayoneApiConstants::ONLINE_BANK_TRANSFER_TYPE_IDEAL,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Payone\Form\IdealOnlineTransferSubForm
     */
    protected function addBankGroupType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_BANK_GROUP_TYPE,
            'choice',
            [
                'label' => false,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'empty_value' => false,
                'choices' => $options[static::OPTIONS_FIELD_NAME][static::OPTION_BANK_GROUP_TYPES],
                'constraints' => [],
            ]
        );

        return $this;
    }
}
