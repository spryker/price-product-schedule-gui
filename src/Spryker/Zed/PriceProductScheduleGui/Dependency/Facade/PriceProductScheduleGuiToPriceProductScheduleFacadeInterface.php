<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleExportItemCollectionTransfer;
use Generated\Shared\Transfer\PriceProductScheduleExportItemCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile;

interface PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
{
    public function importPriceProductSchedules(
        PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
    ): PriceProductScheduleListImportResponseTransfer;

    public function createPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    public function updatePriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    public function findPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    public function readPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $uploadedFile,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer;

    public function validateCsvFile(UploadedFile $uploadedFile): PriceProductScheduleCsvValidationResultTransfer;

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesByIdPriceProductScheduleList(
        int $idPriceProductScheduleList
    ): array;

    public function removeAndApplyPriceProductSchedule(int $idPriceProductSchedule): void;

    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer;

    public function updateAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer;

    public function findPriceProductScheduleById(int $idPriceProductSchedule): ?PriceProductScheduleTransfer;

    public function isPriceProductScheduleUnique(PriceProductScheduleTransfer $priceProductScheduleTransfer): bool;

    public function removePriceProductScheduleList(int $idPriceProductScheduleList): PriceProductScheduleListResponseTransfer;

    public function getPriceProductScheduleExportItemCollection(
        PriceProductScheduleExportItemCriteriaTransfer $priceProductScheduleExportItemCriteriaTransfer,
    ): PriceProductScheduleExportItemCollectionTransfer;
}
