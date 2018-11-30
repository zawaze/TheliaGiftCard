<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

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
}