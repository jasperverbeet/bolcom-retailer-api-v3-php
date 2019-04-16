<?php

namespace BolRetailerAPI\Models;


class CommissionModel extends BaseModel
{
    /**
     * @var string
     */
    public $ean;

    /**
     * @var string
     */
    public $condition;

    /**
     * @var float
     */
    public $price;

    /**
     * @var float
     */
    public $fixedAmount;

    /**
     * @var integer
     */
    public $percentage;

    /**
     * @var float
     */
    public $totalCost;

    /**
     * @var float
     */
    public $totalCostWithoutReduction;

    /**
     * @var ReductionModel[]
     */
    public $reductions;


    /**
     * Validate this model, assert that all required values are set.
     */
    public function validate(): void
    {
        $this->assertType($this->ean, 'string');
        $this->assertType($this->condition, 'string');
        $this->assertType($this->fixedAmount, 'double');
        $this->assertType($this->percentage, 'integer');
        $this->assertType($this->totalCost, 'double');

        if (gettype($this->reductions) == 'array')
        {
            $this->reductions = ReductionModel::manyFromResponse($this->reductions);
        }
    }
}