<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Controller;

use Front\Front;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\Delivery\DeliveryPostageEvent;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\AddressQuery;
use Thelia\Model\AreaDeliveryModuleQuery;
use Thelia\Model\Customer;
use Thelia\Model\ProductSaleElementsQuery;
use Thelia\Module\Exception\DeliveryException;
use TheliaGiftCard\Model\GiftCardCustomer;
use TheliaGiftCard\Model\GiftCardCustomerQuery;
use TheliaGiftCard\Model\GiftCardInfoCart;
use TheliaGiftCard\Model\GiftCardInfoCartQuery;
use TheliaGiftCard\Model\GiftCardQuery;
use TheliaGiftCard\Service\GiftCardAmountSpendService;

class GiftCardCartController extends BaseFrontController
{
    public function addGiftCardAction()
    {
        $this->checkAuth();

        $form = $this->createForm('add.code.card.gift');

        try {

            $codeForm = $this->validateForm($form);
            $code = $codeForm->get('code_gift_card')->getData();

            $giftCard = GiftCardQuery::create()
                ->filterByCode($code)
                ->findOne();

            if (null === $giftCard) {
                throw new FormValidationException('Gift Card Code inalid');
            }

            $gifCardUser = GiftCardCustomerQuery::create()
                ->filterByCardId($giftCard->getId())
                ->findOne();

            if (null !== $gifCardUser) {
                throw new FormValidationException('Gift Card Code inalid');
            }

            $newGifCardUser = new GiftCardCustomer();

            $user = $this->getRequest()->getSession()->getCustomerUser()->getId();

            $newGifCardUser
                ->setCustomerId($user)
                ->setCardId($giftCard->getId())
                ->setUsedAmount(0)
                ->save();

            return $this->generateSuccessRedirect($form);

        } catch (FormValidationException $error_message) {

            $form->setErrorMessage($error_message);

            return $this->generateRedirectFromRoute('customer.home');
        } catch (\Exception $e) {
            throw new \Exception('Gift Card save error' . $e);
        }

    }

    public function spendAmountAction()
    {
        $this->checkAuth();

        $form = $this->createForm('spend.amount.card.gift');

        try {
            $amountForm = $this->validateForm($form);

            $amount = $amountForm->get('amount_used')->getData();
            $code = $amountForm->get('gift_card_code')->getData();

            /** @var GiftCardAmountSpendService $gifCardService */
            $gifCardService = $this->container->get('giftcard.amount.spend.service');

            $order = $this->getSession()->getOrder();

            $customerId = $this->getSession()->getCustomerUser()->getId();

            if (null == $order || null == $customerId) {
                return;
            }

            $this->getDelivery($order);

            $gifCardService->applyGiftCardDiscountInCartAndOrder($amount, $code, $customerId, $this->getSession(),
                $this->getDispatcher());

            return $this->generateRedirectFromRoute('order.invoice');

        } catch (FormValidationException $error_message) {

            $form->setErrorMessage($error_message);

            $this->getParserContext()
                ->addForm($form)
                ->setGeneralError($error_message);

            return $this->generateRedirectFromRoute('order.invoice');
        }
    }

    public function deleteAmountAction()
    {

    }

    public function getDelivery($order)
    {
        $deliveryModule = $order->getModuleRelatedByDeliveryModuleId();
        $deliveryAddress = AddressQuery::create()->findPk($order->getChoosenDeliveryAddress());

        /* check that the delivery address belongs to the current customer */
        if ($deliveryAddress->getCustomerId() !== $this->getSecurityContext()->getCustomerUser()->getId()) {
            throw new \Exception(
                $this->getTranslator()->trans(
                    "Delivery address does not belong to the current customer",
                    [],
                    Front::MESSAGE_DOMAIN
                )
            );
        }

        /* check that the delivery module fetches the delivery address area */
        if (null === AreaDeliveryModuleQuery::create()->findByCountryAndModule(
                $deliveryAddress->getCountry(),
                $deliveryModule
            )) {
            throw new \Exception(
                $this->getTranslator()->trans(
                    "Delivery module cannot be use with selected delivery address",
                    [],
                    Front::MESSAGE_DOMAIN
                )
            );
        }

        /* get postage amount */
        $moduleInstance = $deliveryModule->getDeliveryModuleInstance($this->container);

        $cart = $this->getSession()->getSessionCart($this->getDispatcher());
        $deliveryPostageEvent = new DeliveryPostageEvent($moduleInstance, $cart, $deliveryAddress);

        $this->getDispatcher()->dispatch(
            TheliaEvents::MODULE_DELIVERY_GET_POSTAGE,
            $deliveryPostageEvent
        );

        if (!$deliveryPostageEvent->isValidModule() || null === $deliveryPostageEvent->getPostage()) {
            throw new DeliveryException(
                $this->getTranslator()->trans('The delivery module is not valid.', [], Front::MESSAGE_DOMAIN)
            );
        }

        $postage = $deliveryPostageEvent->getPostage();


        $orderEvent = new OrderEvent($order);
        $orderEvent->setDeliveryAddress($deliveryAddress->getId());
        $orderEvent->setDeliveryModule($deliveryModule->getId());
        $orderEvent->setPostage($postage->getAmount());
        $orderEvent->setPostageTax($postage->getAmountTax());
        $orderEvent->setPostageTaxRuleTitle($postage->getTaxRuleTitle());

        $this->getDispatcher()->dispatch(TheliaEvents::ORDER_SET_DELIVERY_ADDRESS, $orderEvent);
        $this->getDispatcher()->dispatch(TheliaEvents::ORDER_SET_DELIVERY_MODULE, $orderEvent);
        $this->getDispatcher()->dispatch(TheliaEvents::ORDER_SET_POSTAGE, $orderEvent);
    }

    public function saveInfoAction()
    {
        $form = $this->createForm('info.card.gift');

        try {
            $this->validateForm($form);

            $cart = $this->getSession()->getSessionCart($this->getDispatcher());
            $cartEvent = new CartEvent($cart);

            $product_id = $form->getForm()->get('product_id')->getData();
            $sponsorName = $form->getForm()->get('sponsor_name')->getData();
            $beneficiaryName = $form->getForm()->get('beneficiary_name')->getData();
            $beneficiaryMessage = $form->getForm()->get('beneficiary_message')->getData();

            if ($product_id) {
                $cartEvent->setQuantity(1);
                $cartEvent->setProduct($product_id);
                $cartEvent->setNewness(1);

                $pse = ProductSaleElementsQuery::create()->findOneByProductId($product_id);
                $cartEvent->setProductSaleElementsId($pse->getId());

                $this->dispatch(TheliaEvents::CART_ADDITEM, $cartEvent);

                $infoGiftCard = new GiftCardInfoCart();

                $infoGiftCard->setCartId($cart->getId());

                $currentCartItem = $cartEvent->getCartItem()->getId();

                $infoGiftCard->setCartItemId($currentCartItem);

                if ($sponsorName) {
                    $infoGiftCard->setSponsorName($sponsorName);
                }

                if ($beneficiaryName) {
                    $infoGiftCard->setBeneficiaryName($beneficiaryName);
                }

                if ($beneficiaryMessage) {
                    $infoGiftCard->setBeneficiaryMessage($beneficiaryMessage);
                }

                $infoGiftCard->save();
            }

        } catch (\Exception $e) {
            return $this->generateRedirectFromRoute('cart.view', ['error_custom' => $e]);
        }

        return $this->generateRedirectFromRoute('cart.view');
    }

    public function sendEmailGiftCardAction()
    {
        $this->checkAuth();
        $form = $this->createForm('send.code.card.gift');

        try {
            $sendForm = $this->validateForm($form);

            $email = $sendForm->get('email')->getData();
            $code = $sendForm->get('code-to-send')->getData();

            /** @var Customer $customer */
            $customer = $this->getSession()->getCustomerUser();

            /** @var MailerFactory $mailer */
            $mailer = $this->getContainer()->get('mailer');
            $mailer->sendEmailMessage(
                'mail_giftcard',
                [$customer->getEmail() => $customer->getLastname() . ' ' . $customer->getFirstname()],
                [$email => $email],
                [
                    'CODE' => $code,
                ]
            );

        } catch (FormValidationException $error_message) {

            $form->setErrorMessage($error_message);
        }

        return $this->generateRedirectFromRoute('customer.home');
    }
}
