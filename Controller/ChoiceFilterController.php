<?php

namespace ChoiceFilter\Controller;

use ChoiceFilter\Model\ChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterQuery;
use Symfony\Component\HttpFoundation\Request;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Tools\URL;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class ChoiceFilterController extends BaseAdminController
{
    public function saveAction(Request $request)
    {
        $data = $request->get('ChoiceFilter');

        if (!empty($data['template_id'])) {
            ChoiceFilterQuery::create()
                ->filterByTemplateId((int) $data['template_id'])
                ->delete();

            $choiceFilterBase = (new ChoiceFilter())
                ->setTemplateId((int) $data['template_id']);

            $template = 'choice-filter/template-edit';
            $parameters = [
                'template_id' => (int) $data['template_id']
            ];
            $redirectUrl = '/admin/configuration/templates/update';

        } elseif (!empty($data['category_id'])) {
            ChoiceFilterQuery::create()
                ->filterByCategoryId((int) $data['category_id'])
                ->delete();

            $choiceFilterBase = (new ChoiceFilter())
                ->setCategoryId((int) $data['category_id']);

            $template = 'choice-filter/category-edit';
            $parameters = [
                'category_id' => (int) $data['category_id']
            ];
            $redirectUrl = '/admin/categories/update';

        } else {
            throw new \Exception("Missing parameter");
        }

        foreach ($data['filter'] as $filter) {
            $choiceFilter = clone $choiceFilterBase;

            $choiceFilter
                ->setVisible((int) $filter['visible'])
                ->setPosition((int) $filter['position']);

            if ($filter['type'] === 'attribute') {
                $choiceFilter
                    ->setAttributeId($filter['id']);
            } elseif ($filter['type'] === 'feature') {
                $choiceFilter
                    ->setFeatureId((int) $filter['id']);
            } else {
                $choiceFilter
                    ->setOtherId((int) $filter['id']);
            }

            $choiceFilter->save();
        }

        $this->getSession()->getFlashBag()->add('choice-filter-success', 'configuration sauvegardée avec succès');

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                $template,
                $parameters
            );
        } else {
            return $this->generateRedirect(
                URL::getInstance()->absoluteUrl($redirectUrl, $parameters)
            );
        }
    }

    public function clearAction(Request $request)
    {
        $data = $request->get('ChoiceFilter');

        if (!empty($data['template_id'])) {
            ChoiceFilterQuery::create()
                ->filterByTemplateId((int) $data['template_id'])
                ->delete();

            $template = 'choice-filter/template-edit';
            $parameters = [
                'template_id' => (int) $data['template_id']
            ];
            $redirectUrl = '/admin/configuration/templates/update';

        } elseif (!empty($data['category_id'])) {
            ChoiceFilterQuery::create()
                ->filterByCategoryId((int) $data['category_id'])
                ->delete();

            $template = 'choice-filter/category-edit';
            $parameters = [
                'category_id' => (int) $data['category_id']
            ];
            $redirectUrl = '/admin/categories/update';

        } else {
            throw new \Exception("Missing parameter");
        }

        $this->getSession()->getFlashBag()->add('choice-filter-success', 'configuration sauvegardée avec succès');

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                $template,
                $parameters
            );
        } else {
            return $this->generateRedirect(
                URL::getInstance()->absoluteUrl($redirectUrl, $parameters)
            );
        }
    }
}
