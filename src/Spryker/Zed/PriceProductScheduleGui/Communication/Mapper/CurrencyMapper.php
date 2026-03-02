<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;

class CurrencyMapper implements CurrencyMapperInterface
{
    public function mapCurrencyEntityToTransfer(SpyCurrency $currencyEntity, CurrencyTransfer $currencyTransfer): CurrencyTransfer
    {
        return $currencyTransfer->fromArray($currencyEntity->toArray(), true);
    }
}
