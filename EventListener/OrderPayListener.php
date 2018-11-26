<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\EventListener;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Service\GiftCardService;

class OrderPayListener implements EventSubscriberInterface
{

    /**
     * @var Container
     */
    private $container;
    /**
     * @var Request
     */
    private $request;

    public function __construct(Container $container, Request $request)
    {

        $this->container = $container;
        $this->request = $request;
    }

    public function setCardAmountOnOrder(OrderEvent $event)
    {
        $cart = $this->request->getSession()->getCart();

        /** @var GiftCardService $gcservice */
        $gcservice = $this->container->get('giftcard.service');

        $order = $event->getPlacedOrder();

        $dataGC  = GiftCardCartQuery::create()->filterByCartId($cart->getId())->findOne();

        $gcservice->setOrderAmountGC($order->getId(), $dataGC->getSpendAmount());
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_PAY => [
                'setCardAmountOnOrder', 128
            ]
        ];
    }
}
