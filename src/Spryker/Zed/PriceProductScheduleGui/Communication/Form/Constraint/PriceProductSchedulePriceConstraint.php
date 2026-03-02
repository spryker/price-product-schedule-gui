<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class PriceProductSchedulePriceConstraint extends Constraint
{
    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE = 'Net price or gross price must be filled';

    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    public function getMessage(): string
    {
        return static::VALIDATION_MESSAGE;
    }
}
