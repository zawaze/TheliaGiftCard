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
use Thelia\Model\CustomerQuery;
use TheliaGiftCard\Model\GiftCardQuery;

class GiftCardList extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('status', null),
            Argument::createIntTypeArgument('card_id', null)
        );
    }

    public function buildModelCriteria()
    {
        $status = $this->getStatus();
        $cardId = $this->getCardId();

        $search = GiftCardQuery::create()
                    ->useGiftCardCustomerQuery()
                    ->endUse();

        if($status){
            $search->filterByStatus($status);
        }

        if($cardId){
            $search->filterById($cardId);
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {

        /** @var GiftCardCustomer $giftCard */
        foreach ($loopResult->getResultDataCollection() as $giftCard) {

            $date = $giftCard->getCreatedAt();

            $loopResultRow = (new LoopResultRow($giftCard))
                ->set('ID', $giftCard->getId())
                ->set('USED_AMOUNT', $giftCard->getUsedAmount())
                ->set('DATE', $date->format('d-m-Y'))
                ->set('INIT_AMOUNT', $giftCard->getVirtualColumn('amount'))
                ->set('CODE', $giftCard->getVirtualColumn('code'))
                ->set('PRODUCT', $giftCard->getVirtualColumn('product_title'));

            $sponsorCustomerID = $giftCard->getVirtualColumn('sponsor_customer_id');
            $sponsorCustomer = CustomerQuery::create()->findPk($sponsorCustomerID);

            if(null !== $sponsorCustomer){
                $loopResultRow->set('SPONSOR_NAME', $sponsorCustomer->getLastname().' '.$sponsorCustomer->getFirstname());
            }

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}