<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    public const REQUEST_KEY_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var string
     */
    public const REQUEST_KEY_ID_PRODUCT_CONCRETE = 'id-product-concrete';

    /**
     * @var string
     */
    public const REQUEST_KEY_ID_PRICE_TYPE = 'id-price-type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function abstractProductTableAction(Request $request): JsonResponse
    {
        $idProductAbstract = $request->query->getInt(static::REQUEST_KEY_ID_PRODUCT_ABSTRACT);
        $idPriceType = $request->query->getInt(static::REQUEST_KEY_ID_PRICE_TYPE);
        $priceProductScheduleTable = $this->getFactory()
            ->createPriceProductScheduleAbstractTable($idProductAbstract, $idPriceType);

        return $this->jsonResponse(
            $priceProductScheduleTable->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function concreteProductTableAction(Request $request): JsonResponse
    {
        $idProductConcrete = $request->query->getInt(static::REQUEST_KEY_ID_PRODUCT_CONCRETE);
        $idPriceType = $request->query->getInt(static::REQUEST_KEY_ID_PRICE_TYPE);
        $idProductAbstract = $request->query->getInt(static::REQUEST_KEY_ID_PRODUCT_ABSTRACT);
        $priceProductScheduleTable = $this->getFactory()
            ->createPriceProductScheduleConcreteTable(
                $idProductConcrete,
                $idProductAbstract,
                $idPriceType,
            );

        return $this->jsonResponse(
            $priceProductScheduleTable->fetchData(),
        );
    }
}
