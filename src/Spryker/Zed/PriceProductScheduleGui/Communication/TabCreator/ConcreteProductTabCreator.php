<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class ConcreteProductTabCreator implements ConcreteProductTabCreatorInterface
{
    /**
     * @var string
     */
    public const TAB_SCHEDULED_PRICE_NAME = 'scheduled_prices';

    /**
     * @var string
     */
    public const TAB_SCHEDULED_PRICE_TITLE = 'Scheduled Prices';

    /**
     * @var string
     */
    public const TAB_SCHEDULED_PRICE_TEMPLATE = '@PriceProductScheduleGui/_partials/schedule-price-concrete-tab.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function createScheduledPriceTabForProductConcrete(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_SCHEDULED_PRICE_NAME)
            ->setTitle(static::TAB_SCHEDULED_PRICE_TITLE)
            ->setTemplate(static::TAB_SCHEDULED_PRICE_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
