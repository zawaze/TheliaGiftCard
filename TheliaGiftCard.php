<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;
use Thelia\Module\BaseModule;
use TheliaGiftCard\Model\GiftCardQuery;

class TheliaGiftCard extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'theliagiftcard';

    /** @var string */
    const MODULE_CODE = 'theliagiftcard';

    const CODES_GIFT_CARD_PRODUCT = [31];
    const ORDER_STATUS_PAID = 2;

    const GIFT_CARD_MESSAGE_NAME = 'send_gift_card';

    const STRING_CODE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

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
        if (null === MessageQuery::create()->findOneByName(self::GIFT_CARD_MESSAGE_NAME)) {
            $message = new Message();
            $email_templates_dir = __DIR__.DS.'templates'.DS.'email'.DS.'default'.DS.'email-gift_card';
            $message
                ->setName(self::GIFT_CARD_MESSAGE_NAME)
                ->setLocale('en_US')
                ->setTitle('GIFT CARD')
                ->setSubject('Gift Card for order {$order_ref}')
                ->setHtmlMessage(file_get_contents($email_templates_dir.'en.html'))
                ->setTextMessage(file_get_contents($email_templates_dir.'en.txt'))
                ->setLocale('fr_FR')
                ->setTitle('Carte Cadeau')
                ->setSubject('Carte cadeau pour votre commande {$order_ref}')
                ->setHtmlMessage(file_get_contents($email_templates_dir.'fr.html'))
                ->setTextMessage(file_get_contents($email_templates_dir.'fr.txt'))
                ->save()
            ;
        }
    }
}
