<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductScheduleGui\Communication\Form;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\MoneyValueSubForm;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Forms;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductScheduleGui
 * @group Communication
 * @group Form
 * @group MoneyValueSubFormTest
 * Add your own group annotations below this line
 */
class MoneyValueSubFormTest extends Unit
{
    protected const int ID_STORE = 1;

    protected const int ID_CURRENCY = 61;

    /**
     * @var \SprykerTest\Zed\PriceProductScheduleGui\PriceProductScheduleGuiCommunicationTester
     */
    protected $tester;

    public function testOnPostSubmitSetsStoreAndCurrencyForeignKeysOnSubmittedMoneyValue(): void
    {
        // Arrange
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setStore((new StoreTransfer())->setIdStore(static::ID_STORE))
            ->setCurrency((new CurrencyTransfer())->setIdCurrency(static::ID_CURRENCY));
        $postSubmitEvent = new PostSubmitEvent(Forms::createFormFactory()->create(FormType::class), $moneyValueTransfer);

        // Act
        (new MoneyValueSubForm())->onPostSubmit($postSubmitEvent);

        // Assert
        $this->assertSame(static::ID_STORE, $moneyValueTransfer->getFkStore());
        $this->assertSame(static::ID_CURRENCY, $moneyValueTransfer->getFkCurrency());
    }
}
