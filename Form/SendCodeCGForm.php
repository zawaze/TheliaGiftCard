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

class SendCodeCGForm extends BaseForm
{
    public function getName()
    {
        return 'send_card_gift';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'email',
                TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ])
            ->add(
                'code-to-send',
                TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]);

    }
}