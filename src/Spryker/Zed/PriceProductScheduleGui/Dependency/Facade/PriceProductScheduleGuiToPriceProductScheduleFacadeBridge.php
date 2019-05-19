<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;

class PriceProductScheduleGuiToPriceProductScheduleFacadeBridge implements PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface $priceProductScheduleFacade
     */
    public function __construct($priceProductScheduleFacade)
    {
        $this->priceProductScheduleFacade = $priceProductScheduleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    public function importPriceProductSchedules(
        PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
    ): PriceProductScheduleListImportResponseTransfer {
        return $this->priceProductScheduleFacade->importPriceProductSchedules($priceProductScheduledListImportRequest);
    }
}