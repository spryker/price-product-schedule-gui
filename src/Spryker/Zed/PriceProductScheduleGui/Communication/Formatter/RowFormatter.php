<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class RowFormatter implements RowFormatterInterface
{
    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d e H:i:s';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface
     */
    protected $currencyMapper;

    public function __construct(
        PriceProductScheduleGuiToMoneyFacadeInterface $moneyFacade,
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        CurrencyMapperInterface $currencyMapper
    ) {
        $this->moneyFacade = $moneyFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyMapper = $currencyMapper;
    }

    public function formatMoney(int $amount, SpyPriceProductSchedule $priceProductScheduleEntity): string
    {
        $currencyTransfer = $this->currencyMapper->mapCurrencyEntityToTransfer(
            $priceProductScheduleEntity->getCurrency(),
            new CurrencyTransfer(),
        );

        $moneyTransfer = $this->createMoneyTransfer($amount, $currencyTransfer);

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }

    public function formatDateTime(DateTime $dateTime, int $fkStore): string
    {
        $dateTime = $this->setTimezoneFromStore(
            $dateTime,
            $fkStore,
        );

        return $dateTime->format(static::DATE_FORMAT);
    }

    protected function setTimezoneFromStore(DateTime $dateTime, int $fkStore): DateTime
    {
        $storeTransfer = $this->storeFacade->getStoreById($fkStore);
        $timeZone = new DateTimeZone($storeTransfer->getTimezone());

        return $dateTime->setTimezone($timeZone);
    }

    protected function createMoneyTransfer(int $amount, CurrencyTransfer $currencyTransfer): MoneyTransfer
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount((string)$amount);
        $moneyTransfer->setCurrency($currencyTransfer);

        return $moneyTransfer;
    }
}
