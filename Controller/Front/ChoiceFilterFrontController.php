<?php

namespace ChoiceFilter\Controller\Front;

use ChoiceFilter\Model\ChoiceFilter;
use ChoiceFilter\Model\ChoiceFilterOtherQuery;
use ChoiceFilter\Model\ChoiceFilterQuery;
use ChoiceFilter\Util;
use OpenApi\Annotations as OA;
use OpenApi\Controller\Front\BaseFrontOpenApiController;
use OpenApi\Model\Api\ModelFactory;
use OpenApi\Service\OpenApiService;
use Propel\Runtime\Collection\ObjectCollection;
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
     *          name="template_id",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
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
     *          @OA\JsonContent(ref="#/components/schemas/ChoiceFilter")
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
        $templateId = $request->get('template_id');
        $categoryId = $request->get('category_id');
        $visible = $request->get('visible', true);

        if (null === $templateId && null === $categoryId) {
            throw new \Exception('The argument template_id or category_id is required');
        }

        if (null !== $templateId && null !== $categoryId) {
            throw new \Exception('The argument template_id or category_id can not be set together');
        }

        if (null !== $categoryId) {
            $category = CategoryQuery::create()->findPk($categoryId);

            $templateIdFind = null;
            $choiceFilters = ChoiceFilterQuery::findChoiceFilterByCategory($category, $templateIdFind, $categoryId);

            if (null === $templateIdFind) {
                $features = new ObjectCollection();
                $attributes = new ObjectCollection();
                $others = new ObjectCollection();
                $choiceFilters = new ObjectCollection();
            } else {
                $features = ChoiceFilterQuery::findFeaturesByTemplateId(
                    $templateIdFind
                );
                $attributes = ChoiceFilterQuery::findAttributesByTemplateId(
                    $templateIdFind
                );
                $others = ChoiceFilterOtherQuery::findOther();
            }

            $filters = Util::merge($choiceFilters, $features, $attributes, $others);
        } elseif (null !== $templateId) {
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

        if (Type\BooleanOrBothType::ANY !== $visible) {
            $visible = $visible ? 1 : 0;
            foreach ($filters as $key => $filter) {
                if ($filter['Visible'] != $visible) {
                    unset($filters[$key]);
                }
            }
        }

        if (empty($filters)) {
            return OpenApiService::jsonResponse([], 404);
        }

        return OpenApiService::jsonResponse(array_map(
            fn ($filters) => $modelFactory->buildModel('ChoiceFilter', $filters, $request->get('locale')),
        $filters
        ));
    }
}
