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
use TheliaGiftCard\Model\GiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery;


class GiftCArdCartUseLoop extends BaseLoop implements PropelSearchLoopInterface
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

        $search = GiftCardCartQuery::create();

        if ($cartId !== null) {
            $search->filterByCartId($cartId);
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var GiftCardCart $cgCart */
        foreach ($loopResult->getResultDataCollection() as $cgCart) {

            $loopResultRow = (new LoopResultRow($cgCart))
                ->set('ID', $cgCart->getId())
                ->set('CARD_ID', $cgCart->getGiftCardId())
                ->set('CART_ID', $cgCart->getCartId())
                ->set('SPEND_AMOUNT', $cgCart->getSpendAmount() + $cgCart->getSpendDelivery());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}