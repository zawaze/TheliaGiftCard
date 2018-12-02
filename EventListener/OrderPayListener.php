<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\Order\OrderPaymentEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CartItem;
use Thelia\Model\CartQuery;
use Thelia\Model\CategoryQuery;
use Thelia\Model\Order;
use Thelia\Model\ProductCategory;
use Thelia\Model\ProductCategoryQuery;
use Thelia\Model\ProductSaleElementsQuery;
use TheliaGiftCard\Model\GiftCard;
use TheliaGiftCard\Model\GiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardQuery;
use TheliaGiftCard\Service\GiftCardService;
use TheliaGiftCard\TheliaGiftCard;

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

    public function creatCodeGiftCard(OrderEvent $event)
    {
        if ($event->getOrder()->getStatusId() == TheliaGiftCard::ORDER_STATUS_PAID) {

            /** @var Order $order */
            $order = $event->getOrder();

            $countMaxbyAmount = $this->getCountGiftCards($order->getOrderProducts());

            /** @var  CartItem $item */
            foreach ($order->getOrderProducts() as $orderProduct) {
                $pse = ProductSaleElementsQuery::create()->findPk($orderProduct->getProductSaleElementsId());

                $productId = $pse->getProduct()->getId();

                $tabProductGiftCard =  $this->getGiftCardProductList();

                if (in_array($productId, $tabProductGiftCard)) {

                    $orederId = $order->getId();

                    $price = $orderProduct->getPrice();

                    $orderProductTaxes = $orderProduct->getOrderProductTaxes()->getData();

                    foreach ($orderProductTaxes as $orderProductTax) {
                        $TaxAmount = $orderProductTax->getAmount();
                    }

                    for ($i = 1; $i <= $orderProduct->getQuantity(); $i++) {

                        $giftCards = GiftCardQuery::create()
                            ->filterByOrderId($order->getId())
                            ->filterByProductId($productId)
                            ->find();

                        if ($giftCards->count() < $countMaxbyAmount[$productId]) {
                            $newGiftCard = new GiftCard();
                            $newGiftCard
                                ->setProductId($productId)
                                ->setSponsorCustomerId($order->getCustomer()->getId())
                                ->setOrderId($orederId)
                                ->setCode(TheliaGiftCard::GENERATE_CODE())
                                ->setAmount($price + $TaxAmount)
                                ->save();
                        }
                    }
                }
            }
        }
    }

    public function setCardAmountOnOrder(OrderEvent $event)
    {
        $cart = $this->request->getSession()->getCart();

        /** @var GiftCardService $gcservice */
        $gcservice = $this->container->get('giftcard.service');

        $order = $event->getPlacedOrder();

        $datasGC = GiftCardCartQuery::create()->filterByCartId($cart->getId())->find();

        /** @var GiftCardCart $dataGC */
        foreach ($datasGC as $dataGC) {

            $gcservice->setOrderAmountGC($order->getId(), $dataGC->getSpendAmount() + $dataGC->getSpendDelivery(), $dataGC->getGiftCardId(), $cart->getCustomer()->getId());

            if($order->getStatusId() == TheliaGiftCard::ORDER_STATUS_PAID) {
                $gcservice->setGiftCardAmount($dataGC->getGiftCardId(), $dataGC->getSpendAmount() + $dataGC->getSpendDelivery(), $cart->getCustomer()->getId());
            }
        }

    }

    public function setAmountOnOrder(OrderEvent $event)
    {
        $order = $event->getOrder();
        $cart = CartQuery::create()->findPk($order->getCartId());

        if ($cart) {

            $giftCardService = $this->container->get('giftcard.amount.spend.service');
            $totalGiftCardAmount = $giftCardService->calculTotalGCDelievery($this->request->getSession()->getCart());

            if (0 < $totalGiftCardAmount) {

                $totalPriceCart = $giftCardService->getTotalPriceOrder($cart);
                $totalOrderPrice = $totalPriceCart + $order->getPostage() - $order->getDiscount();

                if ($totalGiftCardAmount == $totalOrderPrice) {
                    $order->setPostage(0);
                    $order->setDiscount($totalGiftCardAmount);
                } else {
                    if ($totalGiftCardAmount <= $totalPriceCart) {
                        $order->setDiscount($totalGiftCardAmount);
                    } else {
                        $delta = $totalGiftCardAmount - $totalPriceCart;
                        $order->setDiscount($totalPriceCart);
                        $order->setPostage($order->getPostage() - $delta);
                    }
                }

                $order->save();

                return $order;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_UPDATE_STATUS => ['creatCodeGiftCard', 128],
            TheliaEvents::ORDER_PAY => ['setCardAmountOnOrder', 128],
            TheliaEvents::ORDER_BEFORE_PAYMENT => ['setAmountOnOrder', 128],
        ];
    }

    private function getCountGiftCards($orderProducts)
    {
        $cpt = [];

        foreach ($orderProducts as $orderProduct) {
            $pse = ProductSaleElementsQuery::create()->findPk($orderProduct->getProductSaleElementsId());

            $productId = $pse->getProduct()->getId();

            $tabProductGiftCard =  $this->getGiftCardProductList();

            if (in_array($productId, $tabProductGiftCard)) {

                if (isset($cpt[$productId])) {
                    $cpt[$productId] += $orderProduct->getQuantity();
                } else {
                    $cpt[$productId] = $orderProduct->getQuantity();
                }
            }
        }

        return $cpt;
    }

    private function getGiftCardProductList()
    {
        $tab =[];

        $category =  CategoryQuery::create()->findPk(TheliaGiftCard::getGiftCardCategoryId());

        if(null !== $category){
            $products = ProductCategoryQuery::create()
                ->filterByCategoryId($category->getId())
                ->find();

            /** @var ProductCategory $product */
            foreach ($products as $product){
                $tab [] = $product->getProductId();
            }
        }

        return $tab;
    }
}