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

class AddCardGiftCustomerForm extends BaseForm
{
    public function getName()
    {
        return 'add_code_card_gift';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'code_gift_card',
                TextType::class,
                [
                    'label' => $this->translator->trans('FORM_ADD_CODE_CARD_GIFT', [], TheliaGiftCard::DOMAIN_NAME),
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