<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT = 'idProduct';

    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @var string
     */
    protected const REDIRECT_URL_PRODUCT_CONCRETE_PATTERN = '/product-management/edit/variant?id-product=%s&id-product-abstract=%s#tab-content-scheduled_prices';

    /**
     * @var string
     */
    protected const REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN = '/product-management/edit?id-product-abstract=%s#tab-content-scheduled_prices';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS = 'Scheduled price has been successfully saved';

    /**
     * @var string
     */
    protected const KEY_TITLE = 'title';

    /**
     * @var string
     */
    protected const KEY_FORM = 'form';

    /**
     * @var string
     */
    protected const KEY_REDIRECT_URL = 'redirectUrl';

    /**
     * @var string
     */
    protected const KEY_ID_PRODUCT = 'idProduct';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $priceProductScheduleTransfer = $this->setProductIdentifierFromRequest(
            $request,
            new PriceProductScheduleTransfer(),
        );

        $priceProductScheduleFormDataProvider = $this->getFactory()->createPriceProductScheduleFormDataProvider();
        $form = $this->getFactory()
            ->createPriceProductScheduleForm($priceProductScheduleFormDataProvider, $priceProductScheduleTransfer);

        $form->handleRequest($request);
        $redirectUrl = $this->getRedirectUrlFromRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSubmitForm($form, $redirectUrl);
        }

        [$title, $idProduct] = $this->getFactory()
            ->createPriceProductScheduleDataExtractor()
            ->extractTitleAndIdProductFromPriceProductScheduleTransfer($priceProductScheduleTransfer);

        return $this->viewResponse([
            static::KEY_FORM => $form->createView(),
            static::KEY_TITLE => $title,
            static::KEY_ID_PRODUCT => $idProduct,
            static::KEY_REDIRECT_URL => $redirectUrl,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(FormInterface $form, string $redirectUrl): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer */
        $priceProductScheduleTransfer = $form->getData();
        $priceProductScheduleResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->createAndApplyPriceProductSchedule($priceProductScheduleTransfer);

        if ($priceProductScheduleResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($priceProductScheduleResponseTransfer->getErrors() as $priceProductScheduleErrorTransfer) {
            $this->addErrorMessage($priceProductScheduleErrorTransfer->getMessage());
        }

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    protected function setProductIdentifierFromRequest(
        Request $request,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = new PriceProductTransfer();
        $idProduct = $request->query->getInt(static::PARAM_ID_PRODUCT) ?: null;
        $idProductAbstract = $request->query->getInt(static::PARAM_ID_PRODUCT_ABSTRACT) ?: null;
        $priceProductTransfer = $this->setProductIdentifierToPriceProductTransfer(
            $priceProductTransfer,
            $idProduct,
            $idProductAbstract,
        );

        return $priceProductScheduleTransfer->setPriceProduct($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int|null $idProduct
     * @param int|null $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setProductIdentifierToPriceProductTransfer(
        PriceProductTransfer $priceProductTransfer,
        ?int $idProduct,
        ?int $idProductAbstract
    ): PriceProductTransfer {
        if ($idProduct === null) {
            return $priceProductTransfer->setIdProductAbstract($idProductAbstract);
        }

        return $priceProductTransfer->setIdProduct($idProduct);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getRedirectUrlFromRequest(Request $request): string
    {
        $idProductAbstract = $request->query->get(static::PARAM_ID_PRODUCT_ABSTRACT);
        $idProductConcrete = $request->query->get(static::PARAM_ID_PRODUCT);

        if ($idProductConcrete !== null) {
            return sprintf(static::REDIRECT_URL_PRODUCT_CONCRETE_PATTERN, $idProductConcrete, $idProductAbstract);
        }

        return sprintf(static::REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
    }
}
