<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\Exporter\PriceProductScheduleCsvExporterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Exporter\PriceProductScheduleLeanCsvExporter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractor;
use Spryker\Zed\PriceProductScheduleGui\Communication\Extractor\PriceProductScheduleDataExtractorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleDateConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductSchedulePriceConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleUniqueConstraint;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProdductScheduleDeleteForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleListDeleteForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleListForm;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleDeleteFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleListFormDataProvider;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer\DateTransformer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer\PriceTransformer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapper;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectStrategyResolver;
use Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectStrategyResolverInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectToProductAbstract;
use Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectToProductConcrete;
use Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectToScheduleList;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter\TableFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter\TableFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportErrorListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\ImportSuccessListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleListTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleTableForEditList;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ViewExpanderTableFactoryInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToLocaleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleGuiCommunicationFactory extends AbstractCommunicationFactory implements ViewExpanderTableFactoryInterface
{
    public function createAbstractProductTabCreator(): AbstractProductTabCreatorInterface
    {
        return new AbstractProductTabCreator();
    }

    public function createConcreteProductTabCreator(): ConcreteProductTabCreatorInterface
    {
        return new ConcreteProductTabCreator();
    }

    public function createRowFormatter(): RowFormatterInterface
    {
        return new RowFormatter($this->getMoneyFacade(), $this->getStoreFacade(), $this->createCurrencyMapper());
    }

    public function createCurrencyMapper(): CurrencyMapperInterface
    {
        return new CurrencyMapper();
    }

    public function createAbstractProductViewExpander(): AbstractProductViewExpanderInterface
    {
        return new AbstractProductViewExpander(
            $this->getPriceProductFacade(),
            $this->getTranslatorFacade(),
            $this,
        );
    }

    public function createConcreteProductViewExpander(): ConcreteProductViewExpanderInterface
    {
        return new ConcreteProductViewExpander(
            $this->getPriceProductFacade(),
            $this->getTranslatorFacade(),
            $this,
        );
    }

    public function createPriceProductScheduleAbstractTable(
        int $idProductAbstract,
        int $idPriceType
    ): PriceProductScheduleAbstractTable {
        return new PriceProductScheduleAbstractTable(
            $idProductAbstract,
            $idPriceType,
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery(),
        );
    }

    public function createPriceProductScheduleConcreteTable(
        int $idProductConcrete,
        int $idProductAbstract,
        int $idPriceType
    ): PriceProductScheduleConcreteTable {
        return new PriceProductScheduleConcreteTable(
            $idProductConcrete,
            $idProductAbstract,
            $idPriceType,
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery(),
        );
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getPriceProductScheduleImportForm(
        array $options = []
    ): FormInterface {
        return $this
            ->getFormFactory()
            ->create(
                PriceProductScheduleImportFormType::class,
                [],
                $options,
            );
    }

    public function createPriceProductScheduleListDeleteForm(): FormInterface
    {
        return $this->getFormFactory()
            ->create(PriceProductScheduleListDeleteForm::class);
    }

    public function createImportErrorTable(
        PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
    ): ImportErrorListTable {
        return new ImportErrorListTable(
            $priceProductScheduleListImportResponseTransfer,
            $this->getTranslatorFacade(),
        );
    }

    public function createTableFormatter(): TableFormatterInterface
    {
        return new TableFormatter();
    }

    public function createImportSuccessListTable(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): ImportSuccessListTable {
        return new ImportSuccessListTable(
            $priceProductScheduleListTransfer,
            $this->getPriceProductScheduleQuery(),
            $this->getConfig(),
        );
    }

    public function createPriceProductScheduleListTable(): PriceProductScheduleListTable
    {
        return new PriceProductScheduleListTable(
            $this->getPriceProductScheduleListPropelQuery(),
            $this->getUtilDateTimeService(),
            $this->getConfig()->getCsvExportMaxPriceCount(),
        );
    }

    public function createPriceProductScheduleFormDataProvider(): PriceProductScheduleFormDataProvider
    {
        return new PriceProductScheduleFormDataProvider(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade(),
            $this->getLocaleFacade(),
        );
    }

    public function createPriceProductScheduleCsvExporter(): PriceProductScheduleCsvExporterInterface
    {
        return $this->createPriceProductScheduleLeanCsvExporter();
    }

    public function createPriceProductScheduleLeanCsvExporter(): PriceProductScheduleCsvExporterInterface
    {
        return new PriceProductScheduleLeanCsvExporter(
            $this->getConfig(),
            $this->getPriceProductScheduleFacade(),
            $this->getUtilCsvService(),
        );
    }

    public function createPriceProductScheduleForm(
        PriceProductScheduleFormDataProvider $formDataProvider,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): FormInterface {
        return $this->getFormFactory()->create(
            PriceProductScheduleForm::class,
            $formDataProvider->getData($priceProductScheduleTransfer),
            $formDataProvider->getOptions(
                $priceProductScheduleTransfer->getIdPriceProductSchedule(),
            ),
        );
    }

    public function createPriceProductScheduleTable(int $idPriceProductScheduleList): PriceProductScheduleTable
    {
        return new PriceProductScheduleTable(
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery(),
            $idPriceProductScheduleList,
        );
    }

    public function createPriceProductScheduleTableForEditList(int $idPriceProductScheduleList): PriceProductScheduleTableForEditList
    {
        return new PriceProductScheduleTableForEditList(
            $this->createRowFormatter(),
            $this->getPriceProductScheduleQuery(),
            $idPriceProductScheduleList,
        );
    }

    public function createPriceProductScheduleDeleteFormDataProvider(): PriceProductScheduleDeleteFormDataProvider
    {
        return new PriceProductScheduleDeleteFormDataProvider();
    }

    public function createPriceProductScheduleDeleteForm(
        PriceProductScheduleDeleteFormDataProvider $dataProvider,
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        string $redirectUrl
    ): FormInterface {
        return $this->getFormFactory()->create(
            PriceProdductScheduleDeleteForm::class,
            $dataProvider->getData($priceProductScheduleTransfer),
            $dataProvider->getOptions($redirectUrl),
        );
    }

    public function createPriceProductScheduleListFormDataProvider(): PriceProductScheduleListFormDataProvider
    {
        return new PriceProductScheduleListFormDataProvider();
    }

    public function createPriceProductScheduleListForm(
        PriceProductScheduleListFormDataProvider $dataProvider,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): FormInterface {
        return $this->getFormFactory()->create(
            PriceProductScheduleListForm::class,
            $dataProvider->getData($priceProductScheduleListTransfer),
        );
    }

    public function createPriceProductScheduleDateConstraint(): PriceProductScheduleDateConstraint
    {
        return new PriceProductScheduleDateConstraint();
    }

    public function createPriceProductSchedulePriceConstraint(): PriceProductSchedulePriceConstraint
    {
        return new PriceProductSchedulePriceConstraint();
    }

    public function createPriceProductScheduleUniqueConstraint(): PriceProductScheduleUniqueConstraint
    {
        return new PriceProductScheduleUniqueConstraint([
            PriceProductScheduleUniqueConstraint::OPTION_PRICE_PRODUCT_SCHEDULE_FACADE => $this->getPriceProductScheduleFacade(),
            'groups' => [
                PriceProductScheduleForm::GROUP_AFTER,
            ],
        ]);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface<int|null, float|null>
     */
    public function createPriceTransformer(): DataTransformerInterface
    {
        return new PriceTransformer($this->getMoneyFacade());
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface<string|null, \DateTime|null>
     */
    public function createDateTransformer(): DataTransformerInterface
    {
        return new DateTransformer();
    }

    public function createPriceProductScheduleDataExtractor(): PriceProductScheduleDataExtractorInterface
    {
        return new PriceProductScheduleDataExtractor(
            $this->getStoreFacade(),
            $this->createPriceProductScheduleDataFormatter(),
        );
    }

    public function createPriceProductScheduleDataFormatter(): PriceProductScheduleDataFormatterInterface
    {
        return new PriceProductScheduleDataFormatter(
            $this->getProductFacade(),
        );
    }

    public function createPriceProductScheduleRedirectToProductAbstract(): PriceProductScheduleRedirectInterface
    {
        return new PriceProductScheduleRedirectToProductAbstract();
    }

    public function createPriceProductScheduleRedirectToProductConcrete(): PriceProductScheduleRedirectInterface
    {
        return new PriceProductScheduleRedirectToProductConcrete();
    }

    public function createPriceProductScheduleRedirectToScheduleList(): PriceProductScheduleRedirectInterface
    {
        return new PriceProductScheduleRedirectToScheduleList();
    }

    public function createPriceProductScheduleRedirectStrategyResolver(): PriceProductScheduleRedirectStrategyResolverInterface
    {
        return new PriceProductScheduleRedirectStrategyResolver([
            PriceProductScheduleRedirectStrategyResolver::KEY_ABSTRACT_PRODUCT => $this->createPriceProductScheduleRedirectToProductAbstract(),
            PriceProductScheduleRedirectStrategyResolver::KEY_CONCRETE_PRODUCT => $this->createPriceProductScheduleRedirectToProductConcrete(),
            PriceProductScheduleRedirectStrategyResolver::KEY_SCHEDULE_LIST => $this->createPriceProductScheduleRedirectToScheduleList(),
        ]);
    }

    public function getPriceProductFacade(): PriceProductScheduleGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    public function getStoreFacade(): PriceProductScheduleGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_STORE);
    }

    public function getTranslatorFacade(): PriceProductScheduleGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    public function getMoneyFacade(): PriceProductScheduleGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_MONEY);
    }

    public function getPriceProductScheduleFacade(): PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT_SCHEDULE);
    }

    public function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_SCHEDULE);
    }

    public function getPriceProductScheduleListPropelQuery(): SpyPriceProductScheduleListQuery
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_SCHEDULE_LIST);
    }

    public function getUtilCsvService(): PriceProductScheduleGuiToUtilCsvServiceInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::SERVICE_UTIL_CSV);
    }

    public function getCurrencyFacade(): PriceProductScheduleGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_CURRENCY);
    }

    public function getProductFacade(): PriceProductScheduleGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRODUCT);
    }

    public function getUtilDateTimeService(): PriceProductScheduleGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    public function getLocaleFacade(): PriceProductScheduleGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_LOCALE);
    }
}
