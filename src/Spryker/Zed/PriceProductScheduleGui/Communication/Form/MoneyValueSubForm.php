<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Form\Constraint\IntegerMoneyConstraint;
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class MoneyValueSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NET_AMOUNT = 'netAmount';

    /**
     * @var string
     */
    public const FIELD_GROSS_AMOUNT = 'grossAmount';

    /**
     * @var string
     */
    public const FIELD_CURRENCY = 'currency';

    /**
     * @var string
     */
    public const FIELD_STORE = 'store';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
        ]);

        $resolver->setRequired([
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
        ]);

        $resolver->setDefaults([
            PriceProductScheduleFormDataProvider::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCurrency(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CURRENCY, CurrencySubForm::class, [
            'label' => false,
            'data_class' => CurrencyTransfer::class,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStore(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STORE, StoreSubForm::class, [
            'label' => false,
            'data_class' => StoreTransfer::class,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStore($builder, $options)
            ->addCurrency($builder)
            ->addNetPrice($builder, $options)
            ->addGrossPrice($builder, $options);

        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onPostSubmit(FormEvent $event): void
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $event->getData();
        $storeTransfer = $moneyValueTransfer->getStore();
        $currencyTransfer = $moneyValueTransfer->getCurrency();
        $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());
        $moneyValueTransfer->setFkStore($storeTransfer->getIdStore());
        $event->setData($moneyValueTransfer);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addNetPrice(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_NET_AMOUNT, FormattedNumberType::class, [
            'label' => 'Net price',
            'locale' => $options[PriceProductScheduleFormDataProvider::OPTION_LOCALE],
            'required' => false,
            'constraints' => [
                new GreaterThanOrEqual(['value' => 0]),
                new IntegerMoneyConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_NET_AMOUNT)
            ->addModelTransformer($this->getFactory()->createPriceTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addGrossPrice(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_GROSS_AMOUNT, FormattedNumberType::class, [
            'label' => 'Gross price',
            'locale' => $options[PriceProductScheduleFormDataProvider::OPTION_LOCALE],
            'required' => false,
            'constraints' => [
                new GreaterThanOrEqual(['value' => 0]),
                new IntegerMoneyConstraint(),
            ],
        ]);

        $builder->get(static::FIELD_GROSS_AMOUNT)
            ->addModelTransformer($this->getFactory()->createPriceTransformer());

        return $this;
    }
}
