<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria\Translatable;

use TimeShow\Repository\Criteria\AbstractCriteria;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @template TRelated of \Illuminate\Database\Eloquent\Model
 *
 * @extends AbstractCriteria<TModel, TRelated>
 */
class WhereHasTranslation extends AbstractCriteria
{
    /**
     * @var string
     */
    protected string $locale;

    /**
     * @var string
     */
    protected string $attribute;

    /**
     * @var string
     */
    protected string $value;

    /**
     * @var bool
     */
    protected bool $exact;

    /**
     * @var string
     */
    protected string $operator;


    /**
     * @param string $attribute
     * @param string $value
     * @param string $locale
     * @param bool   $exact     if false, looks up as 'like' (adds %)
     */
    public function __construct(string $attribute, string $value, string $locale = null, bool $exact = true)
    {
        $locale ?: app()->getLocale();

        if ( ! $exact && ! preg_match('#^%(.+)%$#', $value)) {
            $value = '%' . $value . '%';
        }

        $this->locale    = $locale;
        $this->attribute = $attribute;
        $this->value     = $value;
        $this->operator  = $exact ? '=' : 'LIKE';
    }


    /**
     * @param TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel> $model
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    protected function applyToQuery(Model|Relation|DatabaseBuilder|EloquentBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        return $model->whereHas(
            'translations',
            function (EloquentBuilder|Relation $query) {

                return $query->where($this->attribute, $this->operator, $this->value)
                    ->where('locale', $this->locale);
            }
        );
    }
}