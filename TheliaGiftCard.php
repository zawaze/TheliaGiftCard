<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Core\Translation\Translator;
use Thelia\Install\Database;
use Thelia\Model\ConfigQuery;
use Thelia\Model\LangQuery;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;
use Thelia\Model\Order;
use Thelia\Model\OrderStatusQuery;
use Thelia\Module\BaseModule;
use Thelia\Module\PaymentModuleInterface;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardCustomerQuery;
use TheliaGiftCard\Model\GiftCardOrderQuery;
use TheliaGiftCard\Model\GiftCardQuery;
use TheliaGiftCard\Service\GiftCardService;

class TheliaGiftCard extends BaseModule implements PaymentModuleInterface
{
    /** @var Translator */
    protected $translator;

    /** @var string */
    const DOMAIN_NAME = 'theliagiftcard';

    /** @var string */
    const MODULE_CODE = 'theliagiftcard';

    const GIFT_CARD_CATEGORY_CONF_NAME  =  'gift_card_category';
    const GIFT_CARD_ORDER_STATUS_CONF_NAME  = 'gift_card_order_status';
    const GIFT_CARD_MODE_CONF_NAME  = 'gift_card_mode';

    // TO DO EN CONFIG //
    const ORDER_STATUS_PAID = 2;
    //---------------------------------------///

    const GIFT_CARD_MESSAGE_NAME = 'send_gift_card';

    const STRING_CODE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    public static function GENERATE_CODE()
    {
        $code = '';

        for ($i = 0; $i < 8; $i++) {
            $code .= self::STRING_CODE[rand() % strlen(self::STRING_CODE)];
        }

        $giftCard = GiftCardQuery::create()
            ->filterByCode($code)
            ->findOne();

        if ($giftCard) {
            self::GENERATE_CODE();
        } else {
            return $code;
        }

        return $code;
    }

    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            GiftCardQuery::create()->findOne();
            GiftCardCartQuery::create()->findOne();
            GiftCardCustomerQuery::create()->findOne();
            GiftCardOrderQuery::create()->findOne();

        } catch (\Exception $e) {
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
        }

        if (null === MessageQuery::create()->findOneByName('mail_giftcard')) {
            $message = new Message();
            $message
                ->setName('mail_giftcard')
                ->setHtmlTemplateFileName('email-gift_card.html')
                ->setHtmlLayoutFileName('')
                ->setTextTemplateFileName('email-gift_card.txt')
                ->setTextLayoutFileName('')
                ->setSecured(0);

            $languages = LangQuery::create()->find();

            foreach ($languages as $language) {
                $locale = $language->getLocale();

                $message->setLocale($locale);

                $message->setSubject(
                    $this->trans('Your Gift Card.', [], $locale)
                );
                $message->setTitle(
                    $this->trans('Gift Card', [], $locale)
                );
            }

            $message->save();
        }
    }

    protected function trans($id, $parameters = [], $locale = null)
    {
        if (null === $this->translator) {
            $this->translator = Translator::getInstance();
        }

        return $this->translator->trans($id, $parameters, self::DOMAIN_NAME, $locale);
    }

    public function getHooks()
    {
        return array(
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "order-invoice.giftcard-form",
                "title" => array(
                    "fr_FR" => "Gift Card invoice Hook",
                    "en_US" => "Gift Card invoice Hook",
                ),
                "description" => array(
                    "fr_FR" => "Gift Card invoice Hook",
                    "en_US" => "Gift Card invoice Hook",
                ),
                "chapo" => array(
                    "fr_FR" => "Gift Card invoice Hook",
                    "en_US" => "Gift Card invoice Hook",
                ),
                "active" => true
            ],
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "order-invoice.cart-giftcard-form",
                "title" => array(
                    "fr_FR" => "Gift Card invoice cart Hook",
                    "en_US" => "Gift Card invoice cart Hook",
                ),
                "description" => array(
                    "fr_FR" => "Gift Card invoice cart Hook",
                    "en_US" => "Gift Card invoice cart Hook",
                ),
                "chapo" => array(
                    "fr_FR" => "Gift Card invoice cart Hook",
                    "en_US" => "Gift Card invoice cart Hook",
                ),
                "active" => true
            ]
        );
    }

    public function isValidPayment()
    {
        if (false !== $this->calculDeltaOrderGiftCard()) {
            return true;
        }

        return false;
    }

    public function pay(Order $order)
    {
        $event = new OrderEvent($order);
        $event->setStatus(OrderStatusQuery::getPaidStatus()->getId());
        $this->getDispatcher()->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $event);
    }

    public function manageStockOnCreation()
    {
        return false;
    }

    public static function getGiftCardCategoryId()
    {
        $categoryId = ConfigQuery::read(TheliaGiftCard::GIFT_CARD_CATEGORY_CONF_NAME, '');
        return intval($categoryId);
    }

    public static function getGiftCardModeId()
    {
        $modeId = ConfigQuery::read(TheliaGiftCard::GIFT_CARD_MODE_CONF_NAME, '');
        return intval($modeId);
    }


    public static function getGiftCardOrderStatusId()
    {
        $osId = ConfigQuery::read(TheliaGiftCard::GIFT_CARD_ORDER_STATUS_CONF_NAME, '');
        return intval($osId);
    }


    private function calculDeltaOrderGiftCard()
    {
        $giftCardService = $this->getContainer()->get('giftcard.amount.spend.service');
        $totalGiftCardAmount = $giftCardService->calculTotalGCDelievery($this->getRequest()->getSession()->getCart());

        if ($totalGiftCardAmount >= $this->getCurrentOrderTotalAmount()) {
            return $totalGiftCardAmount - $this->getCurrentOrderTotalAmount();
        }

        return false;
    }
}
