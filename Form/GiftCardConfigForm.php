<?php
/**
 * Created by PhpStorm.
 * User: zawaze
 * Date: 02/12/18
 * Time: 14:57
 */

namespace TheliaGiftCard\Form;


use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Symfony\Component\Validator\Constraints as Assert;
use Thelia\Model\Category;
use Thelia\Model\CategoryQuery;
use Thelia\Model\Lang;
use TheliaGiftCard\TheliaGiftCard;

class GiftCardConfigForm extends BaseForm
{

    protected $selectedGiftCardCategory;

    protected function buildForm()
    {
        $this->selectedGiftCardCategory = TheliaGiftCard::getGiftCardCategoryId();

        $this->formBuilder
            ->add(
                'gift_card_category',
                ChoiceType::class, [
                    'label' => Translator::getInstance()->trans("Category where the gift card are located"),
                    'label_attr' => array(
                        'for' => 'sample_category'
                    ),
                    'choices' => $this->getAllCategories(),
                    'data' => $this->selectedGiftCardCategory,
                    'constraints' => [
                        new Assert\NotBlank
                    ],
                    'choices_as_values' => true,
                ]
            );
    }

    public function getAllCategories()
    {
        /** @var Lang $lang */
        $lang = $this->request->getSession() ? $this->request->getSession()->getLang(true) : $this->request->lang = Lang::getDefaultLanguage();

        $categories = CategoryQuery::create()
            ->joinWithI18n($lang->getLocale(), Criteria::INNER_JOIN)
            ->find();

        $tabData = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $tabData[$category->getTitle()] = $category->getId();
        }

        return $tabData;
    }

    public function getName()
    {
        return "gift_card_config";
    }
}
