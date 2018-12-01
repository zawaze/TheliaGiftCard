<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Smarty\Plugins;

use TheliaGiftCard\Model\GiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;
use Thelia\Core\HttpFoundation\Request;

class GiftCardSmartyPlugin extends AbstractSmartyPlugin
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor('function', 'getGitCardTotal', $this, 'getGitCardTotalOnCart'),
        );
    }

    public function getGitCardTotalOnCart($params, $smarty)
    {
        $cart = $this->request->getSession()->getCart();

        if (null != $cart) {
            $giftCardsCart = GiftCardCartQuery::create()
                ->filterByCartId($cart->getId())
                ->find();

            $total = 0;

            /** @var GiftCardCart $giftCardCart */
            foreach ($giftCardsCart as $giftCardCart) {
                $total += $giftCardCart->getSpendAmount() + $giftCardCart->getSpendDelivery();
            }

            return $total;
        }
    }
}