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
    public const PAY_METHOD_CHOICES = 'pay_methods';
    public const WIDGET_PAY_METHODS = 'widget_pay_methods';
    public const BILLING_ADDRESS_DATA = 'billing_address_data';
    public const CUSTOMER_DATA = 'customer_data';
    protected const PAYMENT_METHOD = 'klarna';
    protected const FIELD_PAY_METHOD_TYPE = 'payMethod';
    protected const FIELD_PAY_METHOD_TOKEN = 'payMethodToken';
    protected const FORM_TEMPLATE_PATH = '%s/%s';

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::PAYONE_KLARNA;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::PAYONE_KLARNA;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPayMethodField(
            $builder,
            $options[SubFormInterface::OPTIONS_FIELD_NAME][static::PAY_METHOD_CHOICES]
        );
        $this->addPayMethodTokenField($builder);
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return sprintf(self::FORM_TEMPLATE_PATH, PayoneConstants::PROVIDER_NAME, self::PAYMENT_METHOD);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
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
    public function setDefaultOptions(OptionsResolver $resolver): void
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addPayMethodField(FormBuilderInterface $builder, array $choices)
    {
        $choices = ['Choose payment category' => ''] + $choices;

        $builder->add(
            static::FIELD_PAY_METHOD_TYPE,
            ChoiceType::class,
            [
                'label' => false,
                'required' => true,
                'choices' => $choices,
                'data' => '',
                'choice_attr' => function ($val) {
                    return $val === '' ? ['disabled' => 'disabled', 'selected' => 'selected'] : ['disabled' => 'disabled'];
                },
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPayMethodTokenField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PAY_METHOD_TOKEN,
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
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        $view->vars[static::BILLING_ADDRESS_DATA] = $options[SubFormInterface::OPTIONS_FIELD_NAME][static::BILLING_ADDRESS_DATA];
        $view->vars[static::CUSTOMER_DATA] = $options[SubFormInterface::OPTIONS_FIELD_NAME][static::CUSTOMER_DATA];
        $view->vars[static::WIDGET_PAY_METHODS] = $options[SubFormInterface::OPTIONS_FIELD_NAME][static::WIDGET_PAY_METHODS];
    }
}
