<?php
namespace TimeShow\Repository\Criteria\Common;

use TimeShow\Repository\Criteria\AbstractCriteria;
use Illuminate\Database\Eloquent\Builder;

class WithRelations extends AbstractCriteria
{
    /**
     * @var array
     */
    protected $withStatements = [];

    /**
     * @param array $withStatements
     */
    public function __construct(array $withStatements)
    {
        $this->withStatements = $withStatements;
    }


    /**
     * @param Builder $model
     * @return mixed
     */
    public function applyToQuery($model)
    {
        return $model->with($this->withStatements);
    }

}