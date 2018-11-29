<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Loop;


use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\CustomerQuery;
use TheliaGiftCard\Model\GiftCard;
use TheliaGiftCard\Model\GiftCardCustomer;
use TheliaGiftCard\Model\GiftCardCustomerQuery;
use TheliaGiftCard\Model\GiftCardOrder;
use TheliaGiftCard\Model\Map\GiftCardCustomerTableMap;
use TheliaGiftCard\Model\Map\GiftCardTableMap;


class GiftCArdList extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAlphaNumStringTypeArgument('customer_id', null),
            Argument::createIntTypeArgument('card_id', null)
        );
    }

    public function buildModelCriteria()
    {
        $customerId = $this->getCustomerId();
        $cardId = $this->getCardId();

        $search = GiftCardCustomerQuery::create();

        $cardGiftJoin = new Join(
            GiftCardCustomerTableMap::CARD_ID,
            GiftCardTableMap::ID,
            Criteria::JOIN
        );

        $search->addJoinObject($cardGiftJoin, 'cardGiftJoin');

        $search->withColumn(
            GiftCardTableMap::TABLE_NAME . '.' . 'amount','amount'

        );

        $search->withColumn(
            GiftCardTableMap::TABLE_NAME . '.' . 'code','code'

        );

        $search->withColumn(
            GiftCardTableMap::TABLE_NAME . '.' . 'sponsor_customer_id','sponsor_customer_id'

        );

        if ($customerId === 'current') {
            $currentCustomer = $this->securityContext->getCustomerUser();
            if (null === $currentCustomer) {
                return null;
            } else {
                $search->filterByCustomerId($currentCustomer->getId(), Criteria::EQUAL);
            }
        } else {
            if ($customerId !== null) {
                $search->filterByCustomerId($customerId);
            }
        }

        if ($cardId !== null) {
            $search->filterByCardId($cardId);
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
                ->set('CODE', $giftCard->getVirtualColumn('code'));;

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