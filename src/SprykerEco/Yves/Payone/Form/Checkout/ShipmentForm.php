<?php

namespace SprykerEco\Yves\Form\Checkout;

use Generated\Shared\Transfer\ShipmentTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShipmentForm extends AbstractType
{

    const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';
    const OPTION_SHIPMENT_METHODS = 'shipmentMethods';

    const SHIPMENT_PROPERTY_PATH = 'shipment';
    const SHIPMENT_SELECTION = 'shipmentSelection';
    const SHIPMENT_SELECTION_PROPERTY_PATH = self::SHIPMENT_PROPERTY_PATH . '.' . self::SHIPMENT_SELECTION;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addShipmentMethods($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_SHIPMENT_METHODS);
        $resolver->setDefaults([
            'data_class' => ShipmentTransfer::class,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shipmentForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerEco\Yves\Form\Checkout\ShipmentForm
     */
    protected function addShipmentMethods(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ID_SHIPMENT_METHOD, 'choice', [
            'choices' => $options[self::OPTION_SHIPMENT_METHODS],
            'expanded' => true,
            'multiple' => false,
            'required' => true,
            'property_path' => 'shipmentSelection',
            'placeholder' => false,
            'constraints' => [
                new NotBlank(),
            ],
            'label' => false,
        ]);

        return $this;
    }

}
