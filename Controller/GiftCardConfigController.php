<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Event\PdfEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Exception\TheliaProcessException;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Log\Tlog;
use Thelia\Model\ConfigQuery;
use Thelia\Model\CustomerQuery;
use TheliaGiftCard\Model\GiftCardInfoCartQuery;
use TheliaGiftCard\Model\GiftCardQuery;
use TheliaGiftCard\Smarty\Plugins\GiftCardSmartyPlugin;
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

    public function generatePdfAction($code)
    {
        //$this->checkAuth();

        //$form = $this->createForm('send.code.card.gift');

        try {
            //$configPdfForm = $this->validateForm($form);


            //$customerId = $configPdfForm->get('sponsor')->getData();
            //$customer = CustomerQuery::create()->findPk($customerId);


            $html = $this->renderRaw(
                'giftCard',
                array(
                    'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eget aliquam neque. Curabitur in elit eu felis vestibulum venenatis. Morbi eros sem, tristique nec viverra nec, congue sit amet felis. ',
                    'code' => 'FSEZRRQF',//$configPdfForm->get('code-to-send')->getData(),
                    'FIRSTNAME' => 'Bertrand',
                    'LASTNAME' => 'Tourlonias'
                ),
                $this->getTemplateHelper()->getActivePdfTemplate()
            );

            $pdfEvent = new PdfEvent($html);

            $this->dispatch(TheliaEvents::GENERATE_PDF, $pdfEvent);

            if ($pdfEvent->hasPdf()) {
                return $this->pdfResponse($pdfEvent->getPdf(), 'gift_card', 200, true);
            }


        } catch (FormValidationException $error_message) {

            /*$error_message = $error_message->getMessage();
            $form->setErrorMessage($error_message);
            $this->getParserContext()
                ->addForm($form)
                ->setGeneralError($error_message);*/
        }
        return $this->generateRedirect('/admin/module/TheliaGiftCard');
    }

    public function activateGiftCardAction($codeGC)
    {
        $this->checkAuth();

        $giftCard = GiftCardQuery::create()
            ->filterByCode($codeGC)
            ->filterByStatus(0)
            ->findOne();

        if ($giftCard) {
            $giftCard->setStatus(1)
                ->save();
        }

        return $this->generateRedirect('/admin/module/TheliaGiftCard');
    }
}