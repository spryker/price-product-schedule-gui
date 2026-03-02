<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductScheduleDataFormatterInterface
{
    public function formatTitle(PriceProductTransfer $priceProductTransfer): string;

    public function formatTimezoneText(?string $timezone): string;
}
