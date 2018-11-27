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
use TheliaGiftCard\Model\GiftCardOrder;
use TheliaGiftCard\Model\GiftCardOrderQuery;

class GiftCardService
{
    public function setCardOnCart($cart_id, $amount)
    {
        $giftCardCart = GiftCardCartQuery::create()
            ->filterByCartId($cart_id)
            ->findOne();

        if (null === $giftCardCart) {
            $newGiftCardCart = new GiftCardCart();

            $newGiftCardCart
                ->setCartId($cart_id)
                ->setSpendAmount($amount)
                ->save();

            return true;

        } else {
            $giftCardCart
                ->setSpendAmount($amount)
                ->save();
            return true;
        }

        return false;
    }

    public function setOrderAmountGC($orderId, $amount)
    {
        $giftCardOrder = GiftCardOrderQuery::create()
            ->filterByOrderId($orderId)
            ->findOne();

        if (null === $giftCardOrder) {
            $newGiftCardOrder = new GiftCardOrder();

            $newGiftCardOrder
                ->setOrderId($orderId)
                ->setSpendAmount($amount)
                ->save();

            return true;
        }

        return false;
    }
}