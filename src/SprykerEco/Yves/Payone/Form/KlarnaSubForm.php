<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneKlarnaTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlarnaSubForm extends AbstractPayoneSubForm
{
    public const PAYMENT_METHOD = 'klarna';
    public const FIELD_PAY_METHOD_TYPE = 'payMethod';
    public const PAY_METHOD_CHOICES = 'pay_methods';
    public const FIELD_PAY_METHOD_TOKENS = 'payMethodTokens';
    public const BILLING_ADDRESS_DATA = 'billing_address_data';
    public const SHIPPING_ADDRESS_DATA = 'shipping_address_data';
    public const CUSTOMER_DATA = 'customer_data';

    /**
     * @return string
     */
    public function getName()
    {
        return PaymentTransfer::PAYONE_KLARNA;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return PaymentTransfer::PAYONE_KLARNA;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addPayMethods($builder, $options[SubFormInterface::OPTIONS_FIELD_NAME][static::PAY_METHOD_CHOICES]);
        $this->addPayMethodTokens($builder);
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
            'data_class' => PayoneKlarnaTransfer::class,
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
     *
     * @return $this
     */
    protected function addPayMethods(FormBuilderInterface $builder, $choices)
    {
        $builder->add(
            static::FIELD_PAY_METHOD_TYPE,
            ChoiceType::class,
            [
                'label' => false,
                'required' => true,
                'choices' => $choices,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPayMethodTokens(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PAY_METHOD_TOKENS,
            HiddenType::class,
            [
                'label' => false,
                'required' => true,
                'data' => [],
            ]
        );

        return $this;
    }

    /**
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars[static::BILLING_ADDRESS_DATA] = $options[SubFormInterface::OPTIONS_FIELD_NAME][static::BILLING_ADDRESS_DATA];
        $view->vars[static::SHIPPING_ADDRESS_DATA] = $options[SubFormInterface::OPTIONS_FIELD_NAME][static::SHIPPING_ADDRESS_DATA];
        $view->vars[static::CUSTOMER_DATA] = $options[SubFormInterface::OPTIONS_FIELD_NAME][static::CUSTOMER_DATA];
    }
}
