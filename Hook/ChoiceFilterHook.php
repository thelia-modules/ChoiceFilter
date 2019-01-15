<?php

namespace ChoiceFilter\Hook;

use ChoiceFilter\Model\ChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterOtherQuery;
use ChoiceFilter\Model\ChoiceFilterQuery;
use ChoiceFilter\Util;
use Propel\Runtime\Collection\ObjectCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Model\CategoryQuery;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class ChoiceFilterHook extends BaseHook
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onTemplateEditBottom(HookRenderEvent $event)
    {
        $templateId = $event->getArgument('template_id');

        $locales = $this->getEditLocales();

        $features = ChoiceFilterQuery::findFeaturesByTemplateId($templateId, $locales);
        $attributes = ChoiceFilterQuery::findAttributesByTemplateId($templateId, $locales);
        $others = ChoiceFilterOtherQuery::findOther($locales);

        /** @var ChoiceFilter[] $choiceFilters */
        $choiceFilters = ChoiceFilterQuery::create()
            ->filterByTemplateId($templateId)
            ->orderByPosition()
            ->find();

        if (count($choiceFilters)) {
            $enabled = true;
        } else {
            $enabled = false;
        }

        $filters = Util::merge($choiceFilters, $features, $attributes, $others);

        $event->add($this->render(
            'choice-filter/hook/template-edit.bottom.html',
            $event->getArguments() + ['filters' => $filters, 'enabled' => $enabled]
        ));
    }

    public function onCategoryTabContent(HookRenderEvent $event)
    {
        if ($event->getArgument('view') !== 'category') {
            return;
        }

        $locales = $this->getEditLocales();

        $category = CategoryQuery::create()->filterById($event->getArgument('id'))->findOne();

        $templateId = null;
        $categoryId = null;
        $choiceFilters = ChoiceFilterQuery::findChoiceFilterByCategory($category, $templateId, $categoryId);

        $messageInfo = [];
        $enabled = false;

        if ($templateId === null) {
            $features = new ObjectCollection();
            $attributes = new ObjectCollection();
            $others = new ObjectCollection();
            $choiceFilters = new ObjectCollection();

            $messageInfo[] = "Cette catégorie utilise aucune configuration des filtres";
        } else {
            $features = ChoiceFilterQuery::findFeaturesByTemplateId(
                $templateId,
                $locales
            );
            $attributes = ChoiceFilterQuery::findAttributesByTemplateId(
                $templateId,
                $locales
            );
            $others = ChoiceFilterOtherQuery::findOther();

            if (null === $categoryId) {
                $messageInfo[] = "Cette catégorie utilise la configuration du gabarit " . $templateId;
            } elseif ($categoryId == $category->getId()) {
                $enabled = true;
                $messageInfo[] = "Cette catégorie utilise sa propre configuration des filtres";
            } else {
                $messageInfo[] = "Cette catégorie utilise la configuration des filtres de la catégorie " . $categoryId;
            }
        }

        $filters = Util::merge($choiceFilters, $features, $attributes, $others);

        $event->add($this->render(
            'choice-filter/hook/category.tab-content.html',
            $event->getArguments() + ['category_id' => $event->getArgument('id'), 'filters' => $filters, 'enabled' => $enabled, 'messageInfo' => $messageInfo]
        ));
    }

    public function onCategoryEditJs(HookRenderEvent $event)
    {
        $event->add($this->render(
            'choice-filter/hook/category.edit-js.html',
            $event->getArguments()
        ));
    }

    public function onTemplateEditJs(HookRenderEvent $event)
    {
        $event->add($this->render(
            'choice-filter/hook/template.edit-js.html',
            $event->getArguments()
        ));
    }

    /**
     * @return string[] list of locale
     */
    protected function getEditLocales()
    {
        /** @var Session $session */
        $session = $this->requestStack->getCurrentRequest()->getSession();

        $locale = $session->getAdminEditionLang()->getLocale();

        return [$locale];
    }
}
