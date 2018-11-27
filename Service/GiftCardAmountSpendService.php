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

    public function applyGiftCardDiscountInCartAndOrder($amount, Session $session, EventDispatcher $dispatcher)
    {
        $cart = $session->getSessionCart($dispatcher);
        $order = $session->getOrder();

        $taxCountry = $this->taxEngine->getDeliveryCountry();
        /** @noinspection MissingService */
        $taxState = $this->taxEngine->getDeliveryState();

        $totalCart = $cart->getTaxedAmount($taxCountry, false, $taxState);

        $rest = 0;

        if ($totalCart <= $amount) {

            $amountDelevryDiscount = $amount - $totalCart;

            $postage = $order->getPostage();
            $postage = $postage - $amountDelevryDiscount;

            if ($postage < 0) {
                $rest = $amountDelevryDiscount - ($postage * -1);
                $postage = 0;
            }

            $order->setPostage($postage);
        }

        $order->setDiscount($amount - $rest);

        //update cart
        $cart->setDiscount($amount - $rest);
        $cart->save();

        $this->giftCardService->setCardOnCart($cart->getId(), $amount - $rest);
    }
}