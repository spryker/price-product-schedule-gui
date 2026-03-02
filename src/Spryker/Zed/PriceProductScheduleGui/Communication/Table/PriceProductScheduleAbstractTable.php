<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Communication\Controller\IndexController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;

class PriceProductScheduleAbstractTable extends AbstractScheduledPriceTable
{
    /**
     * @var string
     */
    protected const PATTERN_TABLE_IDENTIFIER = 'price-product-schedule-abstract:%s:%s';

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var int
     */
    protected $idPriceType;

    public function __construct(
        int $idProductAbstract,
        int $idPriceType,
        RowFormatterInterface $rowFormatter,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery
    ) {
        parent::__construct($rowFormatter);
        $this->idProductAbstract = $idProductAbstract;
        $this->idPriceType = $idPriceType;
        $this->baseUrl = '/';
        $this->defaultUrl = Url::generate('price-product-schedule-gui/index/abstract-product-table', [
            IndexController::REQUEST_KEY_ID_PRODUCT_ABSTRACT => $idProductAbstract,
            IndexController::REQUEST_KEY_ID_PRICE_TYPE => $idPriceType,
        ])->build();
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = parent::configure($config);
        $this->setTableIdentifier(sprintf(static::PATTERN_TABLE_IDENTIFIER, $this->idProductAbstract, $this->idPriceType));

        return $config;
    }

    protected function prepareQuery(): SpyPriceProductScheduleQuery
    {
        return (new SpyPriceProductScheduleQuery())
            ->leftJoinWithCurrency()
            ->leftJoinWithStore()
            ->filterByFkProductAbstract($this->idProductAbstract)
            ->filterByFkPriceType($this->idPriceType);
    }

    protected function generatePriceProductScheduleRemoveButton(SpyPriceProductSchedule $item): string
    {
        return $this->generateRemoveButton(
            Url::generate('/price-product-schedule-gui/delete', [
                'id-price-product-schedule' => $item->getIdPriceProductSchedule(),
                'id-product-abstract' => $this->idProductAbstract,
            ]),
            'Delete',
        );
    }
}
