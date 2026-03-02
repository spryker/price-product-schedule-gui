<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Symfony\Component\Validator\Constraint;

class PriceProductScheduleUniqueConstraint extends Constraint
{
    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE = 'An identical scheduled price already exists for this product.';

    /**
     * @var string
     */
    public const OPTION_PRICE_PRODUCT_SCHEDULE_FACADE = 'priceProductScheduleFacade';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    public function getPriceProductScheduleFacade(): PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
    {
        return $this->priceProductScheduleFacade;
    }

    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    public function getMessage(): string
    {
        return static::VALIDATION_MESSAGE;
    }
}
