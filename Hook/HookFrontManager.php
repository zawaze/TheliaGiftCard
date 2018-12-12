<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use TheliaGiftCard\TheliaGiftCard;

class HookFrontManager extends BaseHook
{
    public function onAccountBottom(HookRenderEvent $event)
    {
        $event->add(
            $this->render("account-gift-card.html")
        );
    }

    public function onOrderInvoiceBottom(HookRenderEvent $event)
    {
        $event->add(
            $this->render("order-invoice-gift-card.html")
        );
    }

    public function onCartInvoiceBottom(HookRenderEvent $event)
    {
        $event->add(
            $this->render("cart-invoice-gift-card.html", ['total_without_giftcard' =>  $event->getArgument('total')])
        );
    }

    public function onProductAdditional(HookRenderEvent $event)
    {
        $productId = $event->getArgument('product');

        $tabProductGiftCard =  TheliaGiftCard::getGiftCardProductList();

        if (in_array($productId, $tabProductGiftCard)) {
            $event->add(
                $this->render("product-additional-gift-card.html")
            );
        }
    }
}
