<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class PriceProductScheduleFormDataProvider
{
    public const OPTION_DATA_CLASS = 'data_class';
    public const OPTION_STORE_CHOICES = 'store_choices';
    public const OPTION_CURRENCY_CHOICES = 'currency_choices';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        PriceProductScheduleGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return array
     */
    public function getPriceTypeValues(): array
    {
        $priceTypes = $this->priceProductFacade->getPriceTypeValues();
        $result = [];

        foreach ($priceTypes as $priceType) {
            $result[$priceType->getIdPriceType()] = $priceType->getName();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getStoreValues(): array
    {
        $storeCollection = $this->storeFacade->getAllStores();
        $result = [];

        foreach ($storeCollection as $storeTransfer) {
            $result[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $result;
    }

    /**
     * @param int $idStore
     *
     * @return array
     */
    protected function getCurrencyValues(int $idStore): array
    {
        $result = [];
        $storeWithCurrenciesCollection = $this->currencyFacade->getAllStoresWithCurrencies();
        foreach ($storeWithCurrenciesCollection as $storeWithCurrencyTransfer) {
            if ($storeWithCurrencyTransfer->getStore() === null) {
                continue;
            }
            if ($storeWithCurrencyTransfer->getStore()->getIdStore() !== $idStore) {
                continue;
            }
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $result[$currencyTransfer->getIdCurrency()] = $currencyTransfer->getCode();
            }
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function getData(): PriceProductScheduleTransfer
    {
        return new PriceProductScheduleTransfer();
    }

    /**
     * @param int|null $idStore
     *
     * @return array
     */
    public function getOptions(?int $idStore = null): array
    {
        return [
            static::OPTION_DATA_CLASS => PriceProductScheduleTransfer::class,
            static::OPTION_STORE_CHOICES => $this->getStoreValues(),
            static::OPTION_CURRENCY_CHOICES => $this->getCurrencyValues($idStore),
        ];
    }
}
