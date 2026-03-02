<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Extractor;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleDataExtractorInterface
{
    public function extractTitleFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string;

    public function extractTimezoneTextFromPriceProductScheduledTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string;

    public function extractTimezoneFromPriceProductScheduledTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string;

    public function extractTitleAndIdProductFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array;
}
