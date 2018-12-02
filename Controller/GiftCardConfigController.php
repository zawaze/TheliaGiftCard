<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\ConfigQuery;
use TheliaGiftCard\TheliaGiftCard;

class GiftCardConfigController extends BaseFrontController
{
    public function editConfigAction()
    {
        $this->checkAuth();

        $form = $this->createForm('config.card.gift');

        try {

            $configForm = $this->validateForm($form);

            $categoryId = $configForm->get('gift_card_category')->getData();
            $orderStatusId = $configForm->get('gift_card_paid_status')->getData();
            $modeId = $configForm->get('gift_card_mode')->getData();

            ConfigQuery::write(TheliaGiftCard::GIFT_CARD_CATEGORY_CONF_NAME, $categoryId, false, true);
            ConfigQuery::write(TheliaGiftCard::GIFT_CARD_ORDER_STATUS_CONF_NAME, $orderStatusId, false, true);
            ConfigQuery::write(TheliaGiftCard::GIFT_CARD_MODE_CONF_NAME, $modeId, false, true);

        } catch (FormValidationException $error_message) {

            $error_message = $error_message->getMessage();
            $form->setErrorMessage($error_message);
            $this->getParserContext()
                ->addForm($form)
                ->setGeneralError($error_message);
        }

        return $this->generateRedirect('/admin/module/TheliaGiftCard');
    }
}