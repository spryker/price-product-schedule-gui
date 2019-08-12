<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleForm extends AbstractType
{
    public const FIELD_PRICE_PRODUCT = 'priceProduct';
    public const FIELD_SUBMIT = 'submit';
    public const FIELD_ACTIVE_FROM = 'activeFrom';
    public const FIELD_ACTIVE_TO = 'activeTo';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired([
            PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
        ]);

        $resolver->setDefaults([
            'data_class' => PriceProductScheduleTransfer::class,
            'constraints' => [
                $this->getFactory()->createPriceProductScheduleDateConstraint(),
                $this->getFactory()->createPriceProductSchedulePriceConstraint(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPriceProduct($builder)
            ->addActiveFrom($builder)
            ->addActiveTo($builder)
            ->addSubmitField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPriceProduct(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRICE_PRODUCT, PriceProductSubForm::class, [
            'data_class' => PriceProductTransfer::class,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SUBMIT, SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-info',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActiveFrom(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ACTIVE_FROM, DateTimeType::class, [
            'label' => 'Start from (included)',
            'date_widget' => 'single_text',
            'date_format' => 'yyyy-mm-dd',
            'time_widget' => 'choice',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActiveTo(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ACTIVE_TO, DateTimeType::class, [
            'label' => 'Finish at (included)',
            'date_widget' => 'single_text',
            'date_format' => 'yyyy-mm-dd',
            'time_widget' => 'choice',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'price_product_schedule';
    }
}
