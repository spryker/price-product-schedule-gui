<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class PriceProductScheduleDateConstraint extends Constraint
{
    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE = 'Finish at must be greater than start from';

    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    public function getMessage(): string
    {
        return static::VALIDATION_MESSAGE;
    }
}
