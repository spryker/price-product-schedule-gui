<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

class PriceProductScheduleDeleteFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_REDIRECT_URL = 'option_redirect_url';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function getData(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleTransfer
    {
        return $priceProductScheduleTransfer;
    }

    /**
     * @param string $redirectUrl
     *
     * @return array<string, mixed>
     */
    public function getOptions(string $redirectUrl): array
    {
        return [
            static::OPTION_REDIRECT_URL => $redirectUrl,
        ];
    }
}
