<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Install\Database;
use Thelia\Model\LangQuery;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;
use Thelia\Module\BaseModule;
use TheliaGiftCard\Model\GiftCardCartQuery;
use TheliaGiftCard\Model\GiftCardCustomerQuery;
use TheliaGiftCard\Model\GiftCardOrderQuery;
use TheliaGiftCard\Model\GiftCardQuery;

class TheliaGiftCard extends BaseModule
{
    /** @var Translator */
    protected $translator;

    /** @var string */
    const DOMAIN_NAME = 'theliagiftcard';

    /** @var string */
    const MODULE_CODE = 'theliagiftcard';

    const CODES_GIFT_CARD_PRODUCT = [31,32];
    const ORDER_STATUS_PAID = 2;

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
}
