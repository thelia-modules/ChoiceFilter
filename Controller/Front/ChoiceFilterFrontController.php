<?php

namespace ChoiceFilter\Controller\Front;

use ChoiceFilter\Model\Api\CategoryChoiceFilter;
use ChoiceFilter\Model\ChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterOtherQuery;
use ChoiceFilter\Model\ChoiceFilterQuery;
use ChoiceFilter\Util;
use OpenApi\Annotations as OA;
use OpenApi\Controller\Front\BaseFrontOpenApiController;
use OpenApi\Model\Api\ModelFactory;
use OpenApi\Service\OpenApiService;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Symfony\Component\Routing\Annotation\Route;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CategoryQuery;
use Thelia\Type;

/**
 * @Route("/open_api/choice_filters", name="choicefilters")
 */
class ChoiceFilterFrontController extends BaseFrontOpenApiController
{
    /**
     * @Route("", name="_get", methods="GET")
     *
     * @OA\Get(
     *     path="/choice_filters",
     *     tags={"ChoiceFilter"},
     *     summary="get filters",
     *     @OA\Parameter(
     *          name="category_id",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="visible",
     *          in="query",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="categories",
     *                  type="array",
     *                  @OA\Items(
     *                      ref="#/components/schemas/CategoryChoiceFilter"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="brands",
     *                  type="array",
     *                  @OA\Items(
     *                      ref="#/components/schemas/BrandChoiceFilter"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="features",
     *                  type="array",
     *                  @OA\Items(
     *                      ref="#/components/schemas/ChoiceFilter"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="attributes",
     *                  type="array",
     *                  @OA\Items(
     *                      ref="#/components/schemas/ChoiceFilter"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Bad request",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function getChoiceFilters(
        Request $request,
        ModelFactory $modelFactory
    ) {
        $locale = $request->get('locale', $request->getSession()->getLang()->getLocale());

        $categoryId = $request->get('category_id');
        $visible = $request->get('visible', true);

        $features = new ObjectCollection();
        $attributes = new ObjectCollection();
        $others = new ObjectCollection();

        $category = CategoryQuery::create()->findPk($categoryId);

        $categoryChoiceFilters = ChoiceFilterQuery::findChoiceFilterByCategory($category, $templateIdFind);

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
            $visible = $visible ? 1 : 0;
            $filters = array_filter($filters, function ($filter) use ($visible) {return $filter['Visible'] == $visible;});
        }

        $results = [];

        $attributeResults = array_map(
            fn ($filter) => $modelFactory->buildModel('ChoiceFilter', $filter, $locale),
            array_filter($filters, function ($filter) {return $filter['Type'] === "attribute";})
        );

        if (!empty($attributeResults)) {
            $results['attributes'] = $attributeResults;
        }

        $featureResults = array_map(
            fn ($filter) => $modelFactory->buildModel('ChoiceFilter', $filter, $locale),
            array_filter($filters, function ($filter) {return $filter['Type'] === "feature";})
        );

        if (!empty($attributeResults)) {
            $results['features'] = $featureResults;
        }

        $categoryIds = [$categoryId];
        $needCategories = !empty(array_filter($filters, function ($filter) {return $filter['Type'] === "category";}));
        if ($needCategories) {
            $con = Propel::getConnection();
            $stmt = $con->prepare("
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

            $categoryResults = array_map(
                fn ($category) => $modelFactory->buildModel('CategoryChoiceFilter', $category, $locale),
                $stmt->fetchAll(\PDO::FETCH_ASSOC)
            );

            if (!empty($categoryResults)) {
                $results['categories'] = $categoryResults;
            }

            $categoryIds = array_map(
                fn (CategoryChoiceFilter $categoryChoiceFilter) => $categoryChoiceFilter->getId(),
                $categoryResults
            );
        }

        $needBrands = !empty(array_filter($filters, function ($filter) {return $filter['Type'] === "brand";}));
        if ($needBrands) {
            $con = Propel::getConnection();
            $stmt = $con->prepare("
                SELECT DISTINCT brand.id as id, brand.*, bi18n.* FROM brand
                INNER JOIN product p on brand.id = p.brand_id
                LEFT JOIN brand_i18n bi18n on brand.id = bi18n.id AND bi18n.locale = :locale
                INNER JOIN product_category ON p.id = product_category.product_id AND product_category.category_id IN (:categoryIds)
            ");
            $stmt->bindValue(':locale', $locale, \PDO::PARAM_STR);
            $stmt->bindValue(':categoryIds', implode(",", $categoryIds), \PDO::PARAM_STR);
            $stmt->execute();

            $brandResults = array_map(
                fn ($brand) => $modelFactory->buildModel('BrandChoiceFilter', $brand, $locale),
                $stmt->fetchAll(\PDO::FETCH_ASSOC)
            );

            if (!empty($brandResults)) {
                $results['brands'] = $brandResults;
            }
        }

        return OpenApiService::jsonResponse($results);
    }
}
