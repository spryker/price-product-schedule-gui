<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\DateTimePickerType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_PRICE_PRODUCT = 'priceProduct';

    /**
     * @var string
     */
    public const FIELD_SUBMIT = 'submit';

    /**
     * @var string
     */
    public const FIELD_ACTIVE_FROM = 'activeFrom';

    /**
     * @var string
     */
    public const FIELD_ACTIVE_TO = 'activeTo';

    /**
     * Legacy `DateTimeType` format, kept unchanged for installations running spryker/gui older
     * than 5.4.0.
     *
     * @var string
     */
    protected const PATTERN_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * ICU pattern used by the Gui date-time picker. Seconds are intentionally omitted: the picker
     * offers no seconds control, and the values are persisted with seconds by
     * \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer\DateTransformer.
     *
     * @var string
     */
    protected const PATTERN_PICKER_DATE_FORMAT = 'yyyy-MM-dd HH:mm';

    /**
     * @var string
     */
    protected const RANGE_GROUP_ACTIVE = 'price-product-schedule-active';

    /**
     * @var string
     */
    public const GROUP_AFTER = 'After';

    /**
     * @var string
     */
    public const GROUP_DEFAULT = 'Default';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED,
        ]);

        $resolver->setRequired([
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED,
        ]);

        $resolver->setDefaults([
            'data_class' => PriceProductScheduleTransfer::class,
            'validation_groups' => new GroupSequence([
                static::GROUP_DEFAULT,
                static::GROUP_AFTER,
            ]),
            'constraints' => [
                $this->getFactory()->createPriceProductScheduleDateConstraint(),
                $this->getFactory()->createPriceProductSchedulePriceConstraint(),
                $this->getFactory()->createPriceProductScheduleUniqueConstraint(),
            ],
            PriceProductScheduleFormDataProvider::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPriceProduct($builder, $options)
            ->addActiveFrom($builder)
            ->addActiveTo($builder)
            ->addSubmitField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addPriceProduct(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRICE_PRODUCT, PriceProductSubForm::class, [
            'data_class' => PriceProductTransfer::class,
            'label' => false,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES],
            PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES],
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES],
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED => $options[PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED],
            PriceProductScheduleFormDataProvider::OPTION_LOCALE => $options[PriceProductScheduleFormDataProvider::OPTION_LOCALE],
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
            ->add(static::FIELD_SUBMIT, SubmitType::class, [
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
        $builder->add(
            static::FIELD_ACTIVE_FROM,
            $this->getActiveDateFieldType(),
            $this->getActiveFromFieldOptions(),
        );

        $builder->get(static::FIELD_ACTIVE_FROM)
            ->addModelTransformer($this->getFactory()->createDateTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActiveTo(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ACTIVE_TO,
            $this->getActiveDateFieldType(),
            $this->getActiveToFieldOptions(),
        );

        $builder->get(static::FIELD_ACTIVE_TO)
            ->addModelTransformer($this->getFactory()->createDateTransformer());

        return $this;
    }

    protected function getActiveDateFieldType(): string
    {
        if ($this->isGuiDateTimePickerTypeAvailable()) {
            return DateTimePickerType::class;
        }

        return DateTimeType::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getActiveFromFieldOptions(): array
    {
        return $this->getActiveDateFieldOptions(
            'Start from (included)',
            DateTimePickerType::RANGE_ROLE_START,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getActiveToFieldOptions(): array
    {
        return $this->getActiveDateFieldOptions(
            'Finish at (included)',
            DateTimePickerType::RANGE_ROLE_END,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getActiveDateFieldOptions(string $label, string $rangeRole): array
    {
        $options = [
            'label' => $label,
            'constraints' => [
                new NotBlank(),
            ],
        ];

        if ($this->isGuiDateTimePickerTypeAvailable()) {
            return $options + [
                'format' => static::PATTERN_PICKER_DATE_FORMAT,
                'range_group' => static::RANGE_GROUP_ACTIVE,
                'range_role' => $rangeRole,
            ];
        }

        return $options + [
            'html5' => false,
            'date_widget' => 'single_text',
            'format' => static::PATTERN_DATE_FORMAT,
            'time_widget' => 'choice',
        ];
    }

    protected function isGuiDateTimePickerTypeAvailable(): bool
    {
        return class_exists(DateTimePickerType::class);
    }

    public function getBlockPrefix(): string
    {
        return 'price_product_schedule';
    }
}
