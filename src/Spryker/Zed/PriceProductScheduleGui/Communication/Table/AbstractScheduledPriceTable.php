<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use DateTime;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

abstract class AbstractScheduledPriceTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_CURRENCY = 'fk_currency';

    /**
     * @var string
     */
    protected const COL_STORE = 'fk_store';

    /**
     * @var string
     */
    protected const COL_NET_PRICE = 'net_price';

    /**
     * @var string
     */
    protected const COL_GROSS_PRICE = 'gross_price';

    /**
     * @var string
     */
    protected const COL_ACTIVE_FROM = 'active_from';

    /**
     * @var string
     */
    protected const COL_ACTIVE_TO = 'active_to';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const PRICE_NUMERIC_PATTERN = '/[^0-9]+/';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface
     */
    protected $rowFormatter;

    public function __construct(RowFormatterInterface $rowFormatter)
    {
        $this->rowFormatter = $rowFormatter;
    }

    public function getSearchTerm(): array
    {
        $searchTerm = $this->getSearchParameter();

        if (!$this->isSearchTermValid($searchTerm)) {
            return $this->getDefaultSearchTerm();
        }

        $searchTerm[static::PARAMETER_VALUE] = $this->normalizeMoneyValue($searchTerm[static::PARAMETER_VALUE]);

        return $searchTerm;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_CURRENCY => 'Currency',
            static::COL_STORE => 'Store',
            static::COL_NET_PRICE => 'Net price',
            static::COL_GROSS_PRICE => 'Gross price',
            static::COL_ACTIVE_FROM => 'Start from (included)',
            static::COL_ACTIVE_TO => 'Finish at (included)',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setSortable([
            static::COL_CURRENCY,
            static::COL_STORE,
            static::COL_NET_PRICE,
            static::COL_GROSS_PRICE,
            static::COL_ACTIVE_FROM,
            static::COL_ACTIVE_TO,
        ]);

        return $config;
    }

    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();
        $queryResults = $this->runQuery($query, $config, true);

        $priceProductScheduleCollection = [];

        foreach ($queryResults as $priceProductScheduleEntity) {
            $priceProductScheduleCollection[] = $this->generateItem($priceProductScheduleEntity);
        }

        return $priceProductScheduleCollection;
    }

    abstract protected function prepareQuery(): SpyPriceProductScheduleQuery;

    protected function generateItem(SpyPriceProductSchedule $priceProductScheduleEntity): array
    {
        /** @var \DateTime $activeFrom */
        $activeFrom = $priceProductScheduleEntity->getActiveFrom();
        /** @var \DateTime $activeTo */
        $activeTo = $priceProductScheduleEntity->getActiveTo();

        return [
            static::COL_NET_PRICE => $this->formatMoney($priceProductScheduleEntity->getNetPrice(), $priceProductScheduleEntity),
            static::COL_GROSS_PRICE => $this->formatMoney($priceProductScheduleEntity->getGrossPrice(), $priceProductScheduleEntity),
            static::COL_STORE => $priceProductScheduleEntity->getStore()->getName(),
            static::COL_CURRENCY => $priceProductScheduleEntity->getCurrency()->getCode(),
            static::COL_ACTIVE_FROM => $this->formatDateTime($activeFrom, $priceProductScheduleEntity->getFkStore()),
            static::COL_ACTIVE_TO => $this->formatDateTime($activeTo, $priceProductScheduleEntity->getFkStore()),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($priceProductScheduleEntity)),
        ];
    }

    protected function formatDateTime(DateTime $dateTime, int $fkStore): string
    {
        return $this->rowFormatter->formatDateTime($dateTime, $fkStore);
    }

    protected function formatMoney(?int $amount, SpyPriceProductSchedule $priceProductScheduleEntity): ?string
    {
        if ($amount === null) {
            return null;
        }

        return $this->rowFormatter->formatMoney($amount, $priceProductScheduleEntity);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $item
     *
     * @return array<string>
     */
    protected function createActionColumn(SpyPriceProductSchedule $item): array
    {
        return [
            $this->generatePriceProductScheduleEditButton($item),
            $this->generatePriceProductScheduleRemoveButton($item),
        ];
    }

    protected function generatePriceProductScheduleEditButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateEditButton(
            Url::generate('/price-product-schedule-gui/edit', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
            ]),
            'Edit',
        );
    }

    protected function generatePriceProductScheduleRemoveButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateRemoveButton(
            Url::generate('/price-product-schedule-gui/delete', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
            ]),
            'Delete',
        );
    }

    /**
     * @param mixed $searchTerm
     *
     * @return bool
     */
    protected function isSearchTermValid($searchTerm): bool
    {
        return is_array($searchTerm)
            && array_key_exists(static::PARAMETER_VALUE, $searchTerm)
            && is_scalar($searchTerm[static::PARAMETER_VALUE]);
    }

    protected function getDefaultSearchTerm(): array
    {
        return [
            static::PARAMETER_VALUE => '',
        ];
    }

    protected function normalizeMoneyValue(string $moneyValue): string
    {
        $moneyValue = str_replace('.', '', $moneyValue);

        return str_replace(',', '', $moneyValue);
    }
}
