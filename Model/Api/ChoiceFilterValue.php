<?php

namespace ChoiceFilter\Model\Api;

use OpenApi\Annotations as OA;
use OpenApi\Constraint as Constraint;
use OpenApi\Model\Api\BaseApiModel;

/**
 * Class ChoiceFilterValue.
 *
 * @OA\Schema(
 *     schema="ChoiceFilterValue",
 *     title="ChoiceFilterValue",
 * )
 */
class ChoiceFilterValue extends BaseApiModel
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

    public function setId(int $id): ChoiceFilterValue
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): ChoiceFilterValue
    {
        $this->title = $title;

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
    public function setPosition(?int $position): ChoiceFilterValue
    {
        $this->position = $position;

        return $this;
    }
}
