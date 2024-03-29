<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductScheduleGui;

use Codeception\Actor;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilDateTimeServiceBridge;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilDateTimeServiceInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\FileManagerGui\PHPMD)
 */
class PriceProductScheduleGuiCommunicationTester extends Actor
{
    use _generated\PriceProductScheduleGuiCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): PriceProductScheduleGuiToUtilDateTimeServiceInterface
    {
        return new PriceProductScheduleGuiToUtilDateTimeServiceBridge(
            $this->getLocator()->utilDateTime()->service(),
        );
    }
}
