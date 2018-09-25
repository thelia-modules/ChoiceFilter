<?php

namespace ChoiceFilter;

use ChoiceFilter\Model\ChoiceFilterOther;
use Propel\Runtime\Collection\ObjectCollection;
use Thelia\Model\Attribute;
use Thelia\Model\Feature;
use ChoiceFilter\Model\ChoiceFilter as ModelChoiceFilter;

class Util
{
    /**
     * @param ModelChoiceFilter[]|ObjectCollection $choiceFilters
     * @param Feature[]|ObjectCollection $features
     * @param Attribute[]|ObjectCollection $attributes
     * @param ChoiceFilterOther[]|ObjectCollection $others
     * @return array
     */
    public static function merge($choiceFilters, $features, $attributes, $others)
    {
        $featuresArray = array_map(function ($feature) {
            return array_merge($feature, ['Type' => 'feature', 'Visible' => 1]);
        }, $features->toArray());

        $attributesArray = array_map(function ($attribute) {
            return array_merge($attribute, ['Type' => 'attribute', 'Visible' => 1]);
        }, $attributes->toArray());

        $othersArray = array_map(function ($other) {
            return $other;
        }, $others->toArray());

        if (count($choiceFilters)) {
            $merge = [];
            foreach ($choiceFilters as $choiceFilter) {
                if (null !== $attributeId = $choiceFilter->getAttributeId()) {
                    foreach ($attributesArray as $key => $attributeArray) {
                        if ($attributeId == $attributeArray['Id']) {
                            $attributeArray['Visible'] = $choiceFilter->getVisible() ? 1 : 0;
                            $merge[] = $attributeArray;
                            unset($attributesArray[$key]);
                        }
                    }
                } elseif (null !== $featureId = $choiceFilter->getFeatureId()) {
                    foreach ($featuresArray as $key => $featureArray) {
                        if ($featureId == $featureArray['Id']) {
                            $featureArray['Visible'] = $choiceFilter->getVisible() ? 1 : 0;
                            $merge[] = $featureArray;
                            unset($featuresArray[$key]);
                        }
                    }
                } elseif (null !== $type = $choiceFilter->getChoiceFilterOther()->getType()) { // todo ajouter une jointure pour le type
                    foreach ($othersArray as $key => $otherArray) {
                        if ($type == $otherArray['Type']) {
                            $otherArray['Visible'] = $choiceFilter->getVisible() ? 1 : 0;
                            $merge[] = $otherArray;
                            unset($othersArray[$key]);
                        }
                    }
                }
            }

            $merge = array_merge($merge, $attributesArray);
            $merge = array_merge($merge, $featuresArray);
            $merge = array_merge($merge, $othersArray);
        } else {
            $merge = array_merge($attributesArray, $featuresArray);
            $merge = array_merge($merge, $othersArray);
        }

        $p = 1;
        $merge = array_map(function ($array) use (&$p) {
            return array_merge($array, ['Position' => $p++]);
        }, $merge);

        return $merge;
    }
}
