<?php

declare(strict_types=1);

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChoiceFilter\Controller\Front\Support;

use Thelia\Model\AttributeAv;
use Thelia\Model\AttributeAvQuery;
use Thelia\Model\FeatureAv;
use Thelia\Model\FeatureAvQuery;

/**
 * Reproduces the legacy OpenApi JSON shape for /open_api/choice_filters so
 * that integrations relying on the pre-AP4 contract keep working while
 * consumers migrate to the AP4 endpoints.
 */
final class LegacyChoiceFilterSerializer
{
    /**
     * @param array<string, mixed> $filter
     *
     * @return array<string, mixed>
     */
    public static function filterToArray(array $filter, string $locale): array
    {
        $values = [];

        if ('feature' === ($filter['Type'] ?? null)) {
            $values = array_map(
                static fn (FeatureAv $featureAv): array => [
                    'id' => $featureAv->getId(),
                    'title' => $featureAv->getTitle(),
                    'position' => $featureAv->getPosition(),
                ],
                iterator_to_array(
                    FeatureAvQuery::create()
                        ->filterByFeatureId((int) $filter['Id'])
                        ->useFeatureAvI18nQuery()
                            ->filterByLocale($locale)
                        ->endUse()
                        ->find()
                ),
            );
        }

        if ('attribute' === ($filter['Type'] ?? null)) {
            $values = array_map(
                static fn (AttributeAv $attributeAv): array => [
                    'id' => $attributeAv->getId(),
                    'title' => $attributeAv->getTitle(),
                    'position' => $attributeAv->getPosition(),
                ],
                iterator_to_array(
                    AttributeAvQuery::create()
                        ->filterByAttributeId((int) $filter['Id'])
                        ->useAttributeAvI18nQuery()
                            ->filterByLocale($locale)
                        ->endUse()
                        ->find()
                ),
            );
        }

        return [
            'id' => isset($filter['Id']) ? (int) $filter['Id'] : null,
            'title' => $filter['Title'] ?? null,
            'visible' => (bool) ($filter['Visible'] ?? false),
            'position' => isset($filter['Position']) ? (int) $filter['Position'] : null,
            'values' => $values,
        ];
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    public static function categoryToArray(array $row): array
    {
        return [
            'id' => isset($row['id']) ? (int) $row['id'] : null,
            'title' => $row['title'] ?? null,
            'visible' => isset($row['visible']) ? (bool) $row['visible'] : true,
            'position' => isset($row['position']) ? (int) $row['position'] : null,
            'parent' => isset($row['parent']) ? (int) $row['parent'] : null,
        ];
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    public static function brandToArray(array $row): array
    {
        return [
            'id' => isset($row['id']) ? (int) $row['id'] : null,
            'title' => $row['title'] ?? null,
            'visible' => isset($row['visible']) ? (bool) $row['visible'] : true,
            'position' => isset($row['position']) ? (int) $row['position'] : null,
        ];
    }
}
