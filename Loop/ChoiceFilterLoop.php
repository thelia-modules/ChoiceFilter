<?php

namespace ChoiceFilter\Loop;

use ChoiceFilter\Model\ChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterOtherQuery;
use ChoiceFilter\Model\ChoiceFilterQuery;
use ChoiceFilter\Util;
use Propel\Runtime\Collection\ObjectCollection;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\Exception\LoopException;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\Category;
use Thelia\Model\CategoryQuery;
use Thelia\Type;

/**
 * Class ChoiceFilterLoop
 * @package ChoiceFilter\Loop
 *
 * {@inheritdoc}
 * @method int|null getTemplateId()
 * @method int|null getCategoryId()
 * @method string[] getOrder()
 * @method bool|string getVisible()
 *
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class ChoiceFilterLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('template_id'),
            Argument::createIntTypeArgument('category_id'),
            Argument::createBooleanOrBothTypeArgument('visible', true),
            new Argument(
                'order',
                new Type\TypeCollection(
                    new Type\EnumListType(
                        [
                            'position',
                            'position_reverse'
                        ]
                    )
                ),
                'position'
            )
        );
    }

    public function buildArray()
    {
        $templateId = $this->getTemplateId();
        $categoryId = $this->getCategoryId();

        if (null === $templateId && null === $categoryId) {
            throw new LoopException('The argument template_id or category_id is required');
        }

        if (null !== $templateId && null !== $categoryId) {
            throw new LoopException('The argument template_id or category_id can not be set together');
        }

        if (null !== $categoryId) {
            $category = CategoryQuery::create()->findPk($categoryId);

            $templateId = null;
            $categoryId = null;
            $choiceFilters = ChoiceFilterQuery::findChoiceFilterByCategory($category, $templateId, $categoryId);

            if ($templateId === null) {
                $features = new ObjectCollection();
                $attributes = new ObjectCollection();
                $others = new ObjectCollection();
                $choiceFilters = new ObjectCollection();
            } else {
                $features = ChoiceFilterQuery::findFeaturesByTemplateId(
                    $templateId
                );
                $attributes = ChoiceFilterQuery::findAttributesByTemplateId(
                    $templateId
                );
                $others = ChoiceFilterOtherQuery::findOther();
            }

            $filters = Util::merge($choiceFilters, $features, $attributes, $others);
        }

        if (null !== $templateId) {
            $features = ChoiceFilterQuery::findFeaturesByTemplateId($templateId);
            $attributes = ChoiceFilterQuery::findAttributesByTemplateId($templateId);
            $others = ChoiceFilterOtherQuery::findOther();

            /** @var ChoiceFilter[] $choiceFilters */
            $choiceFilters = ChoiceFilterQuery::create()
                ->filterByTemplateId($templateId)
                ->orderByPosition()
                ->find();

            $filters = Util::merge($choiceFilters, $features, $attributes, $others);
        }

        if (null !== $orders = $this->getOrder()) {
            foreach ($orders as $order) {
                switch ($order) {
                    case "position_reverse":
                        return array_reverse($filters);
                        break;
                }
            }
        }

        if ($this->getVisible() !== Type\BooleanOrBothType::ANY) {
            $visible = $this->getVisible() ? 1 : 0;
            foreach ($filters as $key => $filter) {
                if ($filter['Visible'] != $visible) {
                    unset($filters[$key]);
                }
            }
        }

        return $filters;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var ChoiceFilter $choiceFilter */
        foreach ($loopResult->getResultDataCollection() as $choiceFilter) {
            $loopResultRow = new LoopResultRow($choiceFilter);

            $loopResultRow
                ->set('TYPE', $choiceFilter['Type'])
                ->set('ID', $choiceFilter['Id'])
                ->set('VISIBLE', $choiceFilter['Visible'])
                ->set('POSITION', $choiceFilter['Position']);

            $this->addOutputFields($loopResultRow, $choiceFilter);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
