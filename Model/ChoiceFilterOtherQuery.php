<?php

namespace ChoiceFilter\Model;

use ChoiceFilter\Model\Base\ChoiceFilterOtherQuery as BaseChoiceFilterOtherQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for performing query and update operations on the 'choice_filter_other' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ChoiceFilterOtherQuery extends BaseChoiceFilterOtherQuery
{
    public static function findOther($locales = ['en_US'])
    {
        $otherQuery = ChoiceFilterOtherQuery::create();

        $otherQuery->useChoiceFilterOtherI18nQuery(null, Criteria::LEFT_JOIN)
            ->endUse();

        $locales = array_map(function ($value) {
            return '"' . $value . '"';
        }, $locales);

        $otherQuery->addJoinCondition('ChoiceFilterOtherI18n', 'ChoiceFilterOtherI18n.locale IN (' . implode(',', $locales) . ')');

        $otherQuery->withColumn('ChoiceFilterOtherI18n.title', 'Title');

        $otherQuery->groupBy('id');

        return $otherQuery->find();
    }
}
