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

class PriceProductScheduleGuiToPriceProductScheduleFacadeBridge implements PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface $priceProductScheduleFacade
     */
    public function __construct($priceProductScheduleFacade)
    {
        $this->priceProductScheduleFacade = $priceProductScheduleFacade;
    }

    public function importPriceProductSchedules(
        PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
    ): PriceProductScheduleListImportResponseTransfer {
        return $this->priceProductScheduleFacade->importPriceProductSchedules(
            $priceProductScheduledListImportRequest,
        );
    }

    public function createPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        return $this->priceProductScheduleFacade->createPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    public function updatePriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        return $this->priceProductScheduleFacade->updatePriceProductScheduleList($priceProductScheduleListTransfer);
    }

    public function findPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        return $this->priceProductScheduleFacade->findPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    public function readPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $uploadedFile,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer {
        return $this->priceProductScheduleFacade->readPriceProductScheduleImportTransfersFromCsvFile(
            $uploadedFile,
            $productScheduledListImportRequestTransfer,
        );
    }

    public function validateCsvFile(UploadedFile $uploadedFile): PriceProductScheduleCsvValidationResultTransfer
    {
        return $this->priceProductScheduleFacade->validateCsvFile($uploadedFile);
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesByIdPriceProductScheduleList(
        int $idPriceProductScheduleList
    ): array {
        return $this->priceProductScheduleFacade
            ->findPriceProductSchedulesByIdPriceProductScheduleList($idPriceProductScheduleList);
    }

    public function removeAndApplyPriceProductSchedule(int $idPriceProductSchedule): void
    {
        $this->priceProductScheduleFacade->removeAndApplyPriceProductSchedule($idPriceProductSchedule);
    }

    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        return $this->priceProductScheduleFacade
            ->createAndApplyPriceProductSchedule($priceProductScheduleTransfer);
    }

    public function updateAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        return $this->priceProductScheduleFacade
            ->updateAndApplyPriceProductSchedule($priceProductScheduleTransfer);
    }

    public function findPriceProductScheduleById(int $idPriceProductSchedule): ?PriceProductScheduleTransfer
    {
        return $this->priceProductScheduleFacade
            ->findPriceProductScheduleById($idPriceProductSchedule);
    }

    public function isPriceProductScheduleUnique(PriceProductScheduleTransfer $priceProductScheduleTransfer): bool
    {
        return $this->priceProductScheduleFacade->isPriceProductScheduleUnique($priceProductScheduleTransfer);
    }

    public function removePriceProductScheduleList(int $idPriceProductScheduleList): PriceProductScheduleListResponseTransfer
    {
        return $this->priceProductScheduleFacade
            ->removePriceProductScheduleList($idPriceProductScheduleList);
    }

    public function getPriceProductScheduleExportItemCollection(
        PriceProductScheduleExportItemCriteriaTransfer $priceProductScheduleExportItemCriteriaTransfer,
    ): PriceProductScheduleExportItemCollectionTransfer {
        return $this->priceProductScheduleFacade
            ->getPriceProductScheduleExportItemCollection($priceProductScheduleExportItemCriteriaTransfer);
    }
}
