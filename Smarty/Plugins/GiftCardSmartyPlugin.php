<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Smarty\Plugins;

use TheliaGiftCard\Model\GiftCardCart;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardInfoCartQuery;
use TheliaGiftCard\Model\GiftCardQuery;
use TheliaGiftCard\TheliaGiftCard;
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
            new SmartyPluginDescriptor('function', 'getGitCardMode', $this, 'getGitCardMode'),
            new SmartyPluginDescriptor('function', 'getGitCardInfo', $this, 'getGitCardInfo'),
        );
    }

    public function getGitCardTotalOnCart($params, $smarty)
    {
        $cart = $this->request->getSession()->getSessionCart();

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

    public function getGitCardInfo($params, $smarty)
    {
        $cartItemId = $params['cart_item_id'];

        if ($cartItemId) {
            $cart = $this->request->getSession()->getSessionCart();

            $infoGiftCard = GiftCardInfoCartQuery::create()
                ->filterByCartId($cart->getId())
                ->filterByCartItemId($cartItemId)
                ->findOne();

            if ($infoGiftCard) {
                $smarty->assign(['sponsor_name' => $infoGiftCard->getSponsorName()]);
                $smarty->assign(['beneficiary_name' => $infoGiftCard->getBeneficiaryName()]);
                $smarty->assign(['beneficiary_message' => $infoGiftCard->getBeneficiaryMessage()]);
            } else {
                $smarty->assign(['sponsor_name' => ""]);
                $smarty->assign(['beneficiary_name' => ""]);
                $smarty->assign(['beneficiary_message' => ""]);
            }
        }

        $code = $params['code'];

        if ($code) {
            $giftCard = GiftCardQuery::create()->findOneByCode($code);

            if ($giftCard) {
                $infoGiftCard = GiftCardInfoCartQuery::create()->findOneByGiftCardId($giftCard->getId());

                if ($infoGiftCard) {
                    $smarty->assign(['sponsor_name' => $infoGiftCard->getSponsorName()]);
                    $smarty->assign(['beneficiary_name' => $infoGiftCard->getBeneficiaryName()]);
                    $smarty->assign(['beneficiary_message' => $infoGiftCard->getBeneficiaryMessage()]);
                } else {
                    $smarty->assign(['sponsor_name' => ""]);
                    $smarty->assign(['beneficiary_name' => ""]);
                    $smarty->assign(['beneficiary_message' => ""]);
                }
            }
        }
    }

    public function getGitCardMode()
    {
        if (TheliaGiftCard::getGiftCardModeId()) {
            return false;
        }
        return true;
    }
}