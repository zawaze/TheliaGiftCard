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
use TheliaGiftCard\Model\GiftCardOrder;
use TheliaGiftCard\Model\GiftCardOrderQuery;


class GiftCArdOrderUseLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('order_id', null)
        );
    }

    public function buildModelCriteria()
    {
        $orderId = $this->getOrderId();

        $search = GiftCardOrderQuery::create();

        if ($orderId !== null) {
            $search->filterByOrderId($orderId);
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {

        /** @var GiftCardOrder $cgAmount */
        foreach ($loopResult->getResultDataCollection() as $cgAmount) {
            $loopResultRow = (new LoopResultRow($cgAmount))
                ->set('ID', $cgAmount->getId())
                ->set('ORDER_ID', $cgAmount->getOrderId())
                ->set('SPEND_AMOUNT', $cgAmount->getSpendAmount());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}