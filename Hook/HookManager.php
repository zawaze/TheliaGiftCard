<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class HookManager extends BaseHook
{
    public function onAccountBottom(HookRenderEvent $event)
    {

    }

    public function cardGiftAccountUsageInOrder(HookRenderEvent $event)
    {
        $event->add(
            $this->render("gift-card-usage-on-order.html", [ 'order_id' => $event->getArgument('order_id') ])
        );
    }

    public function orderInvoiceForm(HookRenderEvent $event)
    {
        $event->add(
            $this->render("order-invoice-form.html")
        );
    }
}
