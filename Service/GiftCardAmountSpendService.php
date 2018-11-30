<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\TaxEngine\TaxEngine;
use TheliaGiftCard\Model\GiftCard;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardQuery;
use TheliaGiftCard\Model\Map\GiftCardCustomerTableMap;
use TheliaGiftCard\Service\GiftCardService;

class GiftCardAmountSpendService
{
    /** @var TaxEngine $taxEngine */
    private $taxEngine;
    /**
     * @var GiftCardService
     */
    private $giftCardService;

    public function __construct(TaxEngine $taxEngine, GiftCardService $giftCardService)
    {
        $this->taxEngine = $taxEngine;
        $this->giftCardService = $giftCardService;
    }

    public function removeGiftCardDiscountFromCartAndOrder(Session $session, EventDispatcherInterface $dispatcher)
    {

    }

    public function applyGiftCardDiscountInCartAndOrder($amount, $code, $customerId, Session $session, EventDispatcher $dispatcher)
    {
        $cart = $session->getSessionCart($dispatcher);
        $order = $session->getOrder();

        /** @var GiftCard $giftCard */
        $giftCard = GiftCardQuery::create()->findOneByCode($code);

        $currentGiftCard = GiftCardQuery::create()
            ->filterbyCode($code)
            ->useGiftCardCustomerQuery()
            ->filterByCustomerId($customerId)
            ->endUse()
            ->withColumn(GiftCardCustomerTableMap::TABLE_NAME . '.' . 'used_amount', 'used_amount')
            ->findOne();

        /** @var $currentGiftCard GiftCard */
        if (null === $currentGiftCard) {
            return;
        }

        $currentGiftCardCarts = GiftCardCartQuery::create()
            ->filterByGiftCardId($giftCard->getId())
            ->filterByCartId($cart->getId())
            ->find();

        $amountCurrentOnCart = 0;

        foreach($currentGiftCardCarts as  $currentGiftCardCart){
            $amountCurrentOnCart += $currentGiftCardCart->getSpendAmount() + $currentGiftCardCart->getSpendDelivery();
        }

        $initialAmount = $currentGiftCard->getAmount();
        $usedAmount = $currentGiftCard->getVirtualColumn('used_amount');

        $usableAmount = $initialAmount - $usedAmount;

        if ($usableAmount > 0 && $usableAmount >= $amount + $amountCurrentOnCart) {

            $discountBeforeGiftCard = $cart->getDiscount();

            $taxCountry = $this->taxEngine->getDeliveryCountry();
            /** @noinspection MissingService */
            $taxState = $this->taxEngine->getDeliveryState();

            $totalCart = $cart->getTaxedAmount($taxCountry, false, $taxState) - $discountBeforeGiftCard;

            if ($totalCart <= $amount) {

                $amountDelta = $amount - $totalCart;

                $postage = $order->getPostage();

                if ($postage <= $amountDelta) {

                    $amoutDiscountPostage = $postage;
                    $amount = $amount - $amoutDiscountPostage;

                    $postage = 0;

                } else {

                    $amoutDiscountPostage = $amountDelta;
                    $amount = $amount - $amoutDiscountPostage;
                    $postage = $postage - $amountDelta;

                }

                $order->setPostage($postage);
            }

            $cart->setDiscount($discountBeforeGiftCard + $amount)->save();
            $order->setDiscount($discountBeforeGiftCard + $amount);

            $this->giftCardService->setCardOnCart($cart->getId(), $amount, $amoutDiscountPostage, $giftCard->getId());
        }
    }
}