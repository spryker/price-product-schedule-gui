<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;

interface TableFormatterInterface
{
    public function formatAbstractTableToArray(AbstractTable $table): array;
}
