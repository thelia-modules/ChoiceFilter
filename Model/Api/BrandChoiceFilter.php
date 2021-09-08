<?php

namespace ChoiceFilter\Model\Api;

use OpenApi\Annotations as OA;
use OpenApi\Constraint as Constraint;
use OpenApi\Model\Api\BaseApiModel;
use Thelia\Model\AttributeAv;
use Thelia\Model\AttributeAvQuery;
use Thelia\Model\FeatureAv;
use Thelia\Model\FeatureAvQuery;

/**
 * Class BrandChoiceFilter.
 *
 * @OA\Schema(
 *     schema="BrandChoiceFilter",
 *     title="BrandChoiceFilter",
 * )
 */
class BrandChoiceFilter extends BaseApiModel
{
    /**
     * @var int
     * @OA\Property(
     *    type="integer",
     * )
     * @Constraint\NotBlank(groups={"read"})
     */
    protected $id;

    /**
     * @var string
     * @OA\Property(
     *     type="string",
     * )
     */
    protected $title;

    /**
     * @var bool
     * @OA\Property(
     *     type="boolean",
     * )
     */
    protected $visible = true;

    /**
     * @var int
     * @OA\Property(
     *     type="integer",
     * )
     */
    protected $position;


    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): BrandChoiceFilter
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title): BrandChoiceFilter
    {
        $this->title = $title;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(?bool $visible = true): BrandChoiceFilter
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(?int $position): BrandChoiceFilter
    {
        $this->position = $position;

        return $this;
    }
}
