<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Exporter;

use DateTime;
use Generated\Shared\Transfer\CsvFileTransfer;
use Generated\Shared\Transfer\PriceProductScheduleExportItemCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductScheduleExportItemTransfer;
use Generator;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PriceProductScheduleLeanCsvExporter implements PriceProductScheduleCsvExporterInterface
{
    protected const string HEADER_ABSTRACT_SKU = 'abstract_sku';

    protected const string HEADER_CONCRETE_SKU = 'concrete_sku';

    protected const string HEADER_PRICE_TYPE = 'price_type';

    protected const string HEADER_STORE = 'store';

    protected const string HEADER_CURRENCY = 'currency';

    protected const string HEADER_NET_PRICE = 'value_net';

    protected const string HEADER_GROSS_PRICE = 'value_gross';

    protected const string HEADER_FROM = 'from_included';

    protected const string HEADER_TO = 'to_included';

    protected const string FILE_NAME = 'price_product_schedule_export.csv';

    protected const string PATTERN_DATE_TIME = 'Y-m-d\TH:i:s-00:00';

    public function __construct(
        protected PriceProductScheduleGuiConfig $config,
        protected PriceProductScheduleGuiToPriceProductScheduleFacadeInterface $priceProductScheduleFacade,
        protected PriceProductScheduleGuiToUtilCsvServiceInterface $utilCsvService,
    ) {
    }

    public function exportToCsvFile(int $idPriceProductScheduleList): StreamedResponse
    {
        $csvFileTransfer = (new CsvFileTransfer())
            ->setFileName(static::FILE_NAME)
            ->setHeader($this->getHeaders())
            ->addDataGenerator($this->createCsvRowGenerator($idPriceProductScheduleList))
            ->setIsGzipEnabled($this->config->isGzipCsvExportEnabled());

        return $this->utilCsvService->exportFile($csvFileTransfer);
    }

    /**
     * @return array<string>
     */
    protected function getHeaders(): array
    {
        return [
            static::HEADER_ABSTRACT_SKU,
            static::HEADER_CONCRETE_SKU,
            static::HEADER_PRICE_TYPE,
            static::HEADER_STORE,
            static::HEADER_CURRENCY,
            static::HEADER_NET_PRICE,
            static::HEADER_GROSS_PRICE,
            static::HEADER_FROM,
            static::HEADER_TO,
        ];
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Generator<array<int|string|null>>
     */
    protected function createCsvRowGenerator(int $idPriceProductScheduleList): Generator
    {
        $chunkSize = $this->config->getCsvExportChunkSize();
        $lastId = 0;

        do {
            $criteriaTransfer = (new PriceProductScheduleExportItemCriteriaTransfer())
                ->setIdPriceProductScheduleList($idPriceProductScheduleList)
                ->setLastProcessedId($lastId)
                ->setLimit($chunkSize);

            $collectionTransfer = $this->priceProductScheduleFacade
                ->getPriceProductScheduleExportItemCollection($criteriaTransfer);

            $exportItems = $collectionTransfer->getPriceProductScheduleExportItems();
            $itemCount = $exportItems->count();

            foreach ($exportItems as $exportItemTransfer) {
                $lastId = $exportItemTransfer->getIdPriceProductScheduleOrFail();

                yield $this->formatCsvRow($exportItemTransfer);
            }
        } while ($itemCount >= $chunkSize);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleExportItemTransfer $exportItemTransfer
     *
     * @return array<int|string|null>
     */
    protected function formatCsvRow(PriceProductScheduleExportItemTransfer $exportItemTransfer): array
    {
        $concreteSku = $exportItemTransfer->getConcreteSku();
        $abstractSku = $concreteSku === null ? $exportItemTransfer->getAbstractSku() : null;
        $activeFrom = $exportItemTransfer->getActiveFrom();
        $activeTo = $exportItemTransfer->getActiveTo();

        return [
            static::HEADER_ABSTRACT_SKU => $abstractSku,
            static::HEADER_CONCRETE_SKU => $concreteSku,
            static::HEADER_PRICE_TYPE => $exportItemTransfer->getPriceTypeName(),
            static::HEADER_STORE => $exportItemTransfer->getStoreName(),
            static::HEADER_CURRENCY => $exportItemTransfer->getCurrencyCode(),
            static::HEADER_NET_PRICE => $exportItemTransfer->getNetAmount(),
            static::HEADER_GROSS_PRICE => $exportItemTransfer->getGrossAmount(),
            static::HEADER_FROM => $activeFrom !== null ? (new DateTime($activeFrom))->format(static::PATTERN_DATE_TIME) : null,
            static::HEADER_TO => $activeTo !== null ? (new DateTime($activeTo))->format(static::PATTERN_DATE_TIME) : null,
        ];
    }
}
