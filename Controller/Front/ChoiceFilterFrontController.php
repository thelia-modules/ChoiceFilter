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

namespace ChoiceFilter\Controller\Front;

use ChoiceFilter\Controller\Front\Support\LegacyChoiceFilterSerializer;
use ChoiceFilter\Model\ChoiceFilterOtherQuery;
use ChoiceFilter\Model\ChoiceFilterQuery;
use ChoiceFilter\Util;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CategoryQuery;
use Thelia\Type;

/**
 * Backwards-compatibility shim for the legacy `/open_api/choice_filters`
 * endpoint still consumed by integrations relying on the pre-AP4 contract.
 *
 * The canonical API will live under `/api/front/...` (AP 4.3) once
 * native resources are introduced.
 */
#[Route('/open_api/choice_filters', name: 'choicefilter_legacy_choice_filters_front')]
final class ChoiceFilterFrontController extends BaseFrontController
{
    #[Route('', name: '_get', methods: ['GET'])]
    public function getChoiceFilters(Request $request): JsonResponse
    {
        $locale = $request->get('locale', $request->getSession()->getLang()->getLocale());

        $categoryId = $request->get('category_id');
        $visible = $request->get('visible', true);

        $features = new ObjectCollection();
        $attributes = new ObjectCollection();
        $others = new ObjectCollection();
        $templateIdFind = null;

        $category = CategoryQuery::create()->findPk($categoryId);
        $categoryChoiceFilters = new ObjectCollection();

        if (null !== $category) {
            $categoryChoiceFilters = ChoiceFilterQuery::findChoiceFilterByCategory($category, $templateIdFind);
        }

        if (null !== $templateIdFind) {
            $features = ChoiceFilterQuery::findFeaturesByTemplateId(
                $templateIdFind,
                [$locale]
            );
            $attributes = ChoiceFilterQuery::findAttributesByTemplateId(
                $templateIdFind,
                [$locale]
            );
            $others = ChoiceFilterOtherQuery::findOther([$locale]);
        }

        $filters = Util::merge($categoryChoiceFilters, $features, $attributes, $others);

        if (Type\BooleanOrBothType::ANY !== $visible) {
            $visibleInt = $visible ? 1 : 0;
            $filters = array_filter(
                $filters,
                static fn (array $filter): bool => (int) ($filter['Visible'] ?? 0) === $visibleInt,
            );
        }

        $results = [];

        $attributeFilters = array_filter(
            $filters,
            static fn (array $filter): bool => 'attribute' === ($filter['Type'] ?? null),
        );
        if ([] !== $attributeFilters) {
            $results['attributes'] = array_values(array_map(
                static fn (array $filter): array => LegacyChoiceFilterSerializer::filterToArray($filter, $locale),
                $attributeFilters,
            ));
        }

        $featureFilters = array_filter(
            $filters,
            static fn (array $filter): bool => 'feature' === ($filter['Type'] ?? null),
        );
        if ([] !== $featureFilters) {
            $results['features'] = array_values(array_map(
                static fn (array $filter): array => LegacyChoiceFilterSerializer::filterToArray($filter, $locale),
                $featureFilters,
            ));
        }

        $categoryIds = [(int) $categoryId];
        $needCategories = [] !== array_filter(
            $filters,
            static fn (array $filter): bool => 'category' === ($filter['Type'] ?? null),
        );

        if ($needCategories) {
            $categoryRows = $this->fetchCategoryFilterRows($locale, (int) $categoryId);

            if ([] !== $categoryRows) {
                $results['categories'] = array_map(
                    static fn (array $row): array => LegacyChoiceFilterSerializer::categoryToArray($row),
                    $categoryRows,
                );

                $categoryIds = array_map(
                    static fn (array $row): int => (int) $row['id'],
                    $categoryRows,
                );
            }
        }

        $needBrands = [] !== array_filter(
            $filters,
            static fn (array $filter): bool => 'brand' === ($filter['Type'] ?? null),
        );

        if ($needBrands) {
            $brandRows = $this->fetchBrandFilterRows($locale, $categoryIds);

            if ([] !== $brandRows) {
                $results['brands'] = array_map(
                    static fn (array $row): array => LegacyChoiceFilterSerializer::brandToArray($row),
                    $brandRows,
                );
            }
        }

        return $this->legacyJson($results);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchCategoryFilterRows(string $locale, int $categoryId): array
    {
        $connection = Propel::getConnection();
        $stmt = $connection->prepare("
            SELECT category.*, ci18n.title as title FROM category
            LEFT JOIN category c_parent ON category.parent = c_parent.id
            LEFT JOIN category c_parent_2 ON c_parent.parent = c_parent_2.id
            LEFT JOIN category c_parent_3 ON c_parent_2.parent = c_parent_3.id
            LEFT JOIN category c_parent_4 ON c_parent_3.parent = c_parent_4.id
            LEFT JOIN category_i18n ci18n on category.id = ci18n.id AND ci18n.locale = :locale
            WHERE category.id = :categoryId OR c_parent.id = :categoryId OR c_parent_2.id = :categoryId OR c_parent_3.id = :categoryId OR c_parent_4.id = :categoryId
        ");
        $stmt->bindValue(':locale', $locale, \PDO::PARAM_STR);
        $stmt->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array<int, int> $categoryIds
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchBrandFilterRows(string $locale, array $categoryIds): array
    {
        $connection = Propel::getConnection();
        $stmt = $connection->prepare("
            SELECT DISTINCT brand.id as id, brand.*, bi18n.* FROM brand
            INNER JOIN product p on brand.id = p.brand_id
            LEFT JOIN brand_i18n bi18n on brand.id = bi18n.id AND bi18n.locale = :locale
            INNER JOIN product_category ON p.id = product_category.product_id AND product_category.category_id IN (:categoryIds)
        ");
        $stmt->bindValue(':locale', $locale, \PDO::PARAM_STR);
        $stmt->bindValue(':categoryIds', implode(',', $categoryIds), \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function legacyJson(mixed $data, int $status = 200): JsonResponse
    {
        $response = (new JsonResponse())->setContent(json_encode($data));
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->setStatusCode($status);

        return $response;
    }
}
