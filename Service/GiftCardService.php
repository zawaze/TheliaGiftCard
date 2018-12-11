<?php
/**
 * Created by PhpStorm.
 * User: zawaze
 * Date: 26/11/18
 * Time: 00:35
 */

namespace TheliaGiftCard\Service;


use TheliaGiftCard\Model\GiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardCustomer;
use TheliaGiftCard\Model\GiftCardCustomerQuery;
use TheliaGiftCard\Model\GiftCardOrder;
use TheliaGiftCard\Model\GiftCardOrderQuery;
use TheliaGiftCard\Model\GiftCardQuery;

class GiftCardService
{
    public function setCardOnCart($cart_id, $amount, $amountDelivery, $cardId)
    {
        $giftCart = GiftCardCartQuery::create()
            ->filterByCartId($cart_id)
            ->filterByCartId($cardId)
            ->findOne();

        if (null == $giftCart) {
            $newGiftCardCart = new GiftCardCart();

            $newGiftCardCart
                ->setGiftCardId($cardId)
                ->setCartId($cart_id)
                ->setSpendAmount($amount)
                ->setSpendDelivery($amountDelivery)
                ->save();

            return true;
        } else {
            $giftCart
                ->setSpendAmount($amount)
                ->setSpendDelivery($amountDelivery)
                ->save();
        }
    }

    public function setOrderAmountGC($orderId, $amount, $cardId, $customerId, $initialDicount, $initialPostage)
    {
        $cardCustomer = GiftCardCustomerQuery::create()
            ->filterByCustomerId($customerId)
            ->filterByCardId($cardId)
            ->findOne();

        if (null !== $cardCustomer) {
            $newGiftCardOrder = new GiftCardOrder();

            $newGiftCardOrder
                ->setOrderId($orderId)
                ->setSpendAmount($amount)
                ->setGiftCardId($cardId)
                ->setInitialDiscount($initialDicount)
                ->setInitialPostage($initialPostage)
                ->save();
            return true;
        }

        return false;
    }

    public function setGiftCardAmount($cardId, $amount, $customerId)
    {
        $cardCustomer = GiftCardCustomerQuery::create()
            ->filterByCustomerId($customerId)
            ->filterByCardId($cardId)
            ->findOne();

        if (null !== $cardCustomer) {
            $cardCustomer->setUsedAmount($cardCustomer->getUsedAmount() + $amount)->save();
        }

        return false;
    }
}