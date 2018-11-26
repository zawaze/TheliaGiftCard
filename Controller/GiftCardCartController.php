<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Form\Exception\FormValidationException;
use TheliaGiftCard\Service\GiftCardService;
use TheliaGiftCard\TheliaGiftCard;

class GiftCardCartController extends BaseFrontController
{
    public function SpendAmountAction()
    {
        $form = $this->createForm('spend.amount.card.gift');

        try {
            $amountForm = $this->validateForm($form);

            // get cart session
            $cart = $this->getRequest()->getSession()->getSessionCart();

            $discount = $cart->getDiscount();

            /** @var GiftCardService $gcservice */
            $gcservice = $this->getContainer()->get('giftcard.service');

            //set session dicount  and spend amount
            /**  To do gérer l option de  cumul  ou  non */

            $amount = $amountForm->get('amount_used')->getData();

            $test = $gcservice->setCardOnCart($cart->getId(), $amount);

            if ($test) {
                $cart
                    ->setDiscount($discount + $amount)
                    ->save();
            }


            return $this->generateRedirectFromRoute('cart.view');

        } catch (FormValidationException $error_message) {

            $form->setErrorMessage($error_message);

            $this->getParserContext()
                ->addForm($form)
                ->setGeneralError($error_message);


            return $this->generateErrorRedirect($form);
        }

    }

    public function DeleteAmountAction()
    {
        $form = $this->createForm('delete.amount.card.gift');

        try {
            $amountForm = $this->validateForm($form);

            // get cart session
            $cart = $this->getRequest()->getSession()->getSessionCart();

            $discount = $cart->getDiscount();

            //set session dicount  and spend amount

            $amount = $amountForm->get('amount_used')->getData();

            $total = $discount - $amount;

            if (0 < $total) {
                $total = 0;
            }

            $cart
                ->setDiscount($total)
                ->save();

            return $this->generateRedirectFromRoute('cart.view');

        } catch (FormValidationException $error_message) {

            $form->setErrorMessage($error_message);

            $this->getParserContext()
                ->addForm($form)
                ->setGeneralError($error_message);


            return $this->generateErrorRedirect($form);
        }

    }
}
