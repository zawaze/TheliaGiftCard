<?php
/*************************************************************************************/
/*      Copyright (c) BERTRAND TOURLONIAS                                            */
/*      email : btourlonias@openstudio.fr                                            */
/*************************************************************************************/

namespace TheliaGiftCard\Form;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
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
                'message',
                TextareaType::class,[
                'label' => Translator::getInstance()->trans("Your message"),
                'label_attr' => array(
                    'for' => 'sample_category' ),
                'required' => false,
                ])
            ->add(
                'sponsor',
                TextType::class
                )
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