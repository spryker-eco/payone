<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerEco\Yves\Payone\PayoneConfig getConfig()
 */
class PostfinanceCardOnlineTransferSubForm extends OnlineTransferSubForm
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'postfinance_card_online_transfer';

    /**
     * @var string
     */
    public const OPTION_BANK_COUNTRIES = 'postfinance card online transfer bank countries';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER;
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
                'data' => PayoneApiConstants::ONLINE_BANK_TRANSFER_TYPE_POSTFINANCE_CARD,
            ],
        );

        return $this;
    }
}
