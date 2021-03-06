<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Form;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Form\BaseForm;
use TheliaGiftCard\TheliaGiftCard;
use Symfony\Component\Validator\Constraints as Assert;

class DelAmountCGForm extends BaseForm
{
    public function getName()
    {
        return 'delete_gift_card_amount';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'amount_used',
                TextType::class,
                [
                    'label' => $this->translator->trans('FORM_ADD_AMOUNT_CARD_GIFT', [], TheliaGiftCard::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => $this->getName() . '-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]
            );
    }
}