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
 * Class CategoryChoiceFilter.
 *
 * @OA\Schema(
 *     schema="CategoryChoiceFilter",
 *     title="CategoryChoiceFilter",
 * )
 */
class CategoryChoiceFilter extends BaseApiModel
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
     * @var int
     * @OA\Property(
     *     type="integer",
     * )
     */
    protected $parent;


    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): CategoryChoiceFilter
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
    public function setTitle(?string $title): CategoryChoiceFilter
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
    public function setVisible(?bool $visible = true): CategoryChoiceFilter
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
    public function setPosition(?int $position): CategoryChoiceFilter
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }

    /**
     * @param int|null $parent
     * @return CategoryChoiceFilter
     */
    public function setParent(?int $parent): CategoryChoiceFilter
    {
        $this->parent = $parent;

        return $this;
    }
}
