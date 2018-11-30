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

        $initialAmount = $currentGiftCard->getAmount();
        $usedAmount = $currentGiftCard->getVirtualColumn('used_amount');

        $usableAmount = $initialAmount - $usedAmount;

        if ($usableAmount > 0 && $usableAmount >= $amount) {
            $taxCountry = $this->taxEngine->getDeliveryCountry();
            /** @noinspection MissingService */
            $taxState = $this->taxEngine->getDeliveryState();

            $totalCart = $cart->getTaxedAmount($taxCountry, false, $taxState);

            $rest = 0;

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

            $discountBeforeGiftCard = $cart->getDiscount();

            $order
                ->setDiscount($discountBeforeGiftCard + $amount);

            //update cart
            $cart
                ->setDiscount($discountBeforeGiftCard + $amount)
                ->save();

            $this->giftCardService->setCardOnCart($cart->getId(), $amount, $amoutDiscountPostage, $giftCard->getId());
        }
    }
}