<?php
namespace TimeShow\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as DatabaseBuilder;

interface CriteriaInterface
{
    /**
     * @param Model|DatabaseBuilder|EloquentBuilder|RememberableBuilder $model
     * @param BaseRepositoryInterface|ExtendedRepositoryInterface       $repository
     * @return mixed
     */
    public function apply($model, BaseRepositoryInterface $repository);

}