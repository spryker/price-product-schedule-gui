<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceProductScheduleGuiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_ABSTRACT_SKU
     *
     * @var string
     */
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_CONCRETE_SKU
     *
     * @var string
     */
    protected const KEY_CONCRETE_SKU = 'concrete_sku';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_STORE
     *
     * @var string
     */
    protected const KEY_STORE = 'store';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_CURRENCY
     *
     * @var string
     */
    protected const KEY_CURRENCY = 'currency';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_PRICE_TYPE
     *
     * @var string
     */
    protected const KEY_PRICE_TYPE = 'price_type';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_VALUE_NET
     *
     * @var string
     */
    protected const KEY_VALUE_NET = 'value_net';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_VALUE_GROSS
     *
     * @var string
     */
    protected const KEY_VALUE_GROSS = 'value_gross';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_FROM_INCLUDED
     *
     * @var string
     */
    protected const KEY_FROM_INCLUDED = 'from_included';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_TO_INCLUDED
     *
     * @var string
     */
    protected const KEY_TO_INCLUDED = 'to_included';

    /**
     * @var string
     */
    protected const KEY_ID_PRICE_PRODUCT_SCHEDULE = 'ID';

    /**
     * @var string
     */
    protected const FILE_MAX_SIZE = '50M';

    /**
     * @var array<string>
     */
    protected const FILE_MIME_TYPES = ['text/csv', 'text/plain'];

    /**
     * @api
     *
     * @return array<string>
     */
    public function getFieldsList(): array
    {
        return [
            static::KEY_ID_PRICE_PRODUCT_SCHEDULE,
            static::KEY_ABSTRACT_SKU,
            static::KEY_CONCRETE_SKU,
            static::KEY_STORE,
            static::KEY_CURRENCY,
            static::KEY_PRICE_TYPE,
            static::KEY_VALUE_NET,
            static::KEY_VALUE_GROSS,
            static::KEY_FROM_INCLUDED,
            static::KEY_TO_INCLUDED,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultSortFieldForSuccessTable(): string
    {
        return static::KEY_ID_PRICE_PRODUCT_SCHEDULE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIdPriceProductScheduleKey(): string
    {
        return static::KEY_ID_PRICE_PRODUCT_SCHEDULE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAbstractSkuKey(): string
    {
        return static::KEY_ABSTRACT_SKU;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getConcreteSkuKey(): string
    {
        return static::KEY_CONCRETE_SKU;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStoreKey(): string
    {
        return static::KEY_STORE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCurrencyKey(): string
    {
        return static::KEY_CURRENCY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPriceTypeKey(): string
    {
        return static::KEY_PRICE_TYPE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getValueNetKey(): string
    {
        return static::KEY_VALUE_NET;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getValueGrossKey(): string
    {
        return static::KEY_VALUE_GROSS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFromIncludedKey(): string
    {
        return static::KEY_FROM_INCLUDED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getToIncludedKey(): string
    {
        return static::KEY_TO_INCLUDED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMaxFileSize(): string
    {
        return static::FILE_MAX_SIZE;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getFileMimeTypes(): array
    {
        return static::FILE_MIME_TYPES;
    }
}
