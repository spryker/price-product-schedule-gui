<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

use Generated\Shared\Transfer\MoneyTransfer;

interface PriceProductScheduleGuiToMoneyFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer): string;

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value): float;

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value): int;
}
