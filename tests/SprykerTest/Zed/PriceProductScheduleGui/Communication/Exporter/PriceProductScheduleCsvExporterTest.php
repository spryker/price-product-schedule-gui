<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductScheduleGui\Communication\Exporter;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductScheduleGui
 * @group Communication
 * @group Exporter
 * @group PriceProductScheduleCsvExporterTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleCsvExporterTest extends Unit
{
    protected const string EXPECTED_HEADER = 'abstract_sku,concrete_sku,price_type,store,currency,value_net,value_gross,from_included,to_included';

    protected const int COLUMN_ABSTRACT_SKU = 0;

    protected const int COLUMN_CONCRETE_SKU = 1;

    protected const int COLUMN_PRICE_TYPE = 2;

    protected const int COLUMN_STORE = 3;

    protected const int COLUMN_CURRENCY = 4;

    protected const int COLUMN_NET_PRICE = 5;

    protected const int COLUMN_GROSS_PRICE = 6;

    /**
     * @var \SprykerTest\Zed\PriceProductScheduleGui\PriceProductScheduleGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testLeanExporterProducesValidCsvWithCorrectColumnPositions(): void
    {
        // Arrange
        [$idPriceProductScheduleList, $storeTransfer, $currencyTransfer, $priceTypeTransfer] = $this->seedScheduleData();

        $factory = $this->getFactory();
        $exporter = $factory->createPriceProductScheduleLeanCsvExporter();

        // Act
        $response = $exporter->exportToCsvFile($idPriceProductScheduleList);
        $content = $this->getDecompressedResponseContent($response);
        $rows = $this->parseCsvRows($content);

        // Assert — header structure
        $this->assertSame(static::EXPECTED_HEADER, $rows[0]);

        // Assert — data row values are in correct columns
        $dataRow = str_getcsv($rows[1]);
        $this->assertSame($storeTransfer->getName(), $dataRow[static::COLUMN_STORE]);
        $this->assertSame($currencyTransfer->getCode(), $dataRow[static::COLUMN_CURRENCY]);
        $this->assertSame($priceTypeTransfer->getName(), $dataRow[static::COLUMN_PRICE_TYPE]);
        $this->assertSame('300', $dataRow[static::COLUMN_NET_PRICE]);
        $this->assertSame('400', $dataRow[static::COLUMN_GROSS_PRICE]);
    }

    /**
     * @return void
     */
    public function testLeanExporterPlacesAbstractSkuCorrectlyForAbstractProduct(): void
    {
        // Arrange
        [$idPriceProductScheduleList] = $this->seedScheduleData();

        $exporter = $this->getFactory()->createPriceProductScheduleLeanCsvExporter();

        // Act
        $content = $this->getDecompressedResponseContent($exporter->exportToCsvFile($idPriceProductScheduleList));
        $dataRow = str_getcsv($this->parseCsvRows($content)[1]);

        // Assert — abstract product: abstract_sku filled, concrete_sku empty
        $this->assertNotEmpty($dataRow[static::COLUMN_ABSTRACT_SKU], 'Abstract SKU should be filled for abstract-only schedule');
        $this->assertEmpty($dataRow[static::COLUMN_CONCRETE_SKU], 'Concrete SKU should be empty for abstract-only schedule');
    }

    /**
     * @return void
     */
    public function testLeanExporterPlacesConcreteSkuCorrectlyForConcreteProduct(): void
    {
        // Arrange
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'g02']);
        $currencyFacade = $this->tester->getLocator()->currency()->facade();
        $currencyTransfer = $currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 700,
                    MoneyValueTransfer::GROSS_AMOUNT => 800,
                ],
            ],
        ]);

        $exporter = $this->getFactory()->createPriceProductScheduleLeanCsvExporter();

        // Act
        $content = $this->getDecompressedResponseContent(
            $exporter->exportToCsvFile($priceProductScheduleListTransfer->getIdPriceProductScheduleList()),
        );
        $dataRow = str_getcsv($this->parseCsvRows($content)[1]);

        // Assert — concrete product: concrete_sku filled, abstract_sku empty
        $this->assertEmpty($dataRow[static::COLUMN_ABSTRACT_SKU], 'Abstract SKU should be empty for concrete schedule');
        $this->assertSame($productConcreteTransfer->getSku(), $dataRow[static::COLUMN_CONCRETE_SKU]);
        $this->assertSame('700', $dataRow[static::COLUMN_NET_PRICE]);
        $this->assertSame('800', $dataRow[static::COLUMN_GROSS_PRICE]);
    }

    /**
     * @return void
     */
    public function testLeanExporterReturnsGzipEncodingHeader(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('isGzipCsvExportEnabled', true);
        [$idPriceProductScheduleList] = $this->seedScheduleData();

        $exporter = $this->getFactory()->createPriceProductScheduleLeanCsvExporter();

        // Act
        $response = $exporter->exportToCsvFile($idPriceProductScheduleList);

        // Assert
        $this->assertSame('gzip', $response->headers->get('Content-Encoding'));
        $this->assertSame('text/csv; charset=utf-8', $response->headers->get('Content-Type'));
    }

    /**
     * @return void
     */
    public function testLeanExporterPaginatesAcrossMultipleChunks(): void
    {
        // Arrange — override chunk size to 2 so pagination kicks in with 5 entries
        $this->tester->mockConfigMethod('getCsvExportChunkSize', 2);

        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'g03']);
        $currencyFacade = $this->tester->getLocator()->currency()->facade();
        $currencyTransfer = $currencyFacade->getByIdCurrency($currencyId);

        $expectedNetAmounts = [100, 200, 300, 400, 500];

        foreach ($expectedNetAmounts as $netAmount) {
            $this->tester->havePriceProductSchedule([
                PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
                PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
                PriceProductScheduleTransfer::IS_CURRENT => false,
                PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
                PriceProductScheduleTransfer::PRICE_PRODUCT => [
                    PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                    PriceProductTransfer::PRICE_TYPE => [
                        PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
                        PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
                    ],
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                        MoneyValueTransfer::FK_CURRENCY => $currencyId,
                        MoneyValueTransfer::CURRENCY => $currencyTransfer,
                        MoneyValueTransfer::NET_AMOUNT => $netAmount,
                        MoneyValueTransfer::GROSS_AMOUNT => $netAmount,
                    ],
                ],
            ]);
        }

        $exporter = $this->getFactory()->createPriceProductScheduleLeanCsvExporter();

        // Act
        $content = $this->getDecompressedResponseContent(
            $exporter->exportToCsvFile($priceProductScheduleListTransfer->getIdPriceProductScheduleList()),
        );
        $rows = $this->parseCsvRows($content);

        // Assert — 1 header + 5 data rows, verifying all chunks were consumed
        $this->assertCount(6, $rows, 'Should have header + 5 data rows across 3 pagination chunks (2+2+1)');

        $actualNetAmounts = [];
        $rowCount = count($rows);

        for ($i = 1; $i < $rowCount; $i++) {
            $actualNetAmounts[] = (int)str_getcsv($rows[$i])[static::COLUMN_NET_PRICE];
        }

        sort($expectedNetAmounts);
        sort($actualNetAmounts);

        $this->assertSame($expectedNetAmounts, $actualNetAmounts, 'All 5 entries should appear across paginated chunks');
    }

    /**
     * @return void
     */
    public function testLeanExporterProducesEmptyCsvForEmptyList(): void
    {
        // Arrange
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();

        $exporter = $this->getFactory()->createPriceProductScheduleLeanCsvExporter();

        // Act
        $content = $this->getDecompressedResponseContent(
            $exporter->exportToCsvFile($priceProductScheduleListTransfer->getIdPriceProductScheduleList()),
        );
        $rows = $this->parseCsvRows($content);

        // Assert
        $this->assertCount(1, $rows, 'Should only contain the header row');
        $this->assertSame(static::EXPECTED_HEADER, $rows[0]);
    }

    /**
     * @return array{int, \Generated\Shared\Transfer\StoreTransfer, \Generated\Shared\Transfer\CurrencyTransfer, \Generated\Shared\Transfer\PriceTypeTransfer}
     */
    protected function seedScheduleData(): array
    {
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = $this->tester->havePriceType();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'g01']);
        $currencyFacade = $this->tester->getLocator()->currency()->facade();
        $currencyTransfer = $currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 300,
                    MoneyValueTransfer::GROSS_AMOUNT => 400,
                ],
            ],
        ]);

        return [
            $priceProductScheduleListTransfer->getIdPriceProductScheduleList(),
            $storeTransfer,
            $currencyTransfer,
            $priceTypeTransfer,
        ];
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory
     */
    protected function getFactory(): PriceProductScheduleGuiCommunicationFactory
    {
        return $this->tester->getFactory();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\StreamedResponse $response
     *
     * @return string
     */
    protected function getDecompressedResponseContent($response): string
    {
        ob_start();
        $response->sendContent();
        $rawContent = ob_get_clean();

        if ($response->headers->get('Content-Encoding') === 'gzip') {
            return gzdecode($rawContent);
        }

        return $rawContent;
    }

    /**
     * @param string $csvContent
     *
     * @return array<string>
     */
    protected function parseCsvRows(string $csvContent): array
    {
        return array_filter(explode("\n", trim($csvContent)));
    }
}
