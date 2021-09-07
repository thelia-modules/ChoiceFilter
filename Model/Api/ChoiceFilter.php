<?php

namespace ChoiceFilter\Model\Api;

use OpenApi\Annotations as OA;
use OpenApi\Constraint as Constraint;
use OpenApi\Model\Api\BaseApiModel;

/**
 * Class ChoiceFilter.
 *
 * @OA\Schema(
 *     schema="ChoiceFilter",
 *     title="ChoiceFilter",
 * )
 */
class ChoiceFilter extends BaseApiModel
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
     * @var string
     * @OA\Property(
     *     type="string",
     * )
     */
    protected $type;

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
     * @var array
     * @OA\Property(
     *     readOnly=true,
     *     type="array",
     *     @OA\Items(
     *          ref="#/components/schemas/ChoiceFilterValue"
     *     )
     * )
     */
    protected $values = [];

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ChoiceFilter
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
    public function setTitle(?string $title): ChoiceFilter
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $title
     */
    public function setType(?string $type): ChoiceFilter
    {
        $this->type = $type;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(?bool $visible = true): ChoiceFilter
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
    public function setPosition(?int $position): ChoiceFilter
    {
        $this->position = $position;

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param int $values
     */
    public function setValues(?array $values): ChoiceFilter
    {
        $this->values = $values;

        return $this;
    }

    protected function getTheliaModel($propelModelName = null)
    {
        return parent::getTheliaModel(\ChoiceFilter\Model\ChoiceFilter::class);
    }
}
