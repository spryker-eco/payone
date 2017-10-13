<?php

namespace SprykerEco\Yves\Payone\Form\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerEco\Yves\Form\Shipment\Form\ShipmentForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SummaryForm extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'shipmentForm',
            ShipmentForm::class,
            array_merge(
                $options,
                [
                    'data_class' => ShipmentTransfer::class,
                    'property_path' => 'shipment'
                ]
            )
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('shipmentMethods');
        $resolver->setDefaults([
            'data_class' => QuoteTransfer::class,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'summaryForm';
    }

}
