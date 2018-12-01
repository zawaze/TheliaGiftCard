<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Loop;


use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\Map\ProductI18nTableMap;
use Thelia\Model\Map\ProductTableMap;
use TheliaGiftCard\Model\GiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\Map\GiftCardTableMap;


class GiftCardCartUseLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('cart_id', null)
        );
    }

    public function buildModelCriteria()
    {
        $cartId = $this->getCartId();

        $search = GiftCardCartQuery::create()
        ->useGiftCardQuery()
            ->useProductQuery()
                ->useProductI18nQuery()
                ->endUse()
            ->endUse()
        ->endUse()
        ->withColumn(ProductI18nTableMap::TABLE_NAME . '.' . 'title','product_title');

        if ($cartId !== null) {
            $search->filterByCartId($cartId);
        }  else {
            $cart = $this->getCurrentRequest()->getSession()->getSessionCart($this->getDispatcher());
            $search->filterByCartId($cart->getId());
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var GiftCardCart $cgCart */
        foreach ($loopResult->getResultDataCollection() as $cgCart) {

            $loopResultRow = (new LoopResultRow($cgCart))
                ->set('ID', $cgCart->getId())
                ->set('PRODUCT', $cgCart->getVirtualColumn("product_title"))
                ->set('CARD_ID', $cgCart->getGiftCardId())
                ->set('CART_ID', $cgCart->getCartId())
                ->set('SPEND_AMOUNT', $cgCart->getSpendAmount() + $cgCart->getSpendDelivery());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }
}