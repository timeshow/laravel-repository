<?php
declare(strict_types=1);
namespace TimeShow\Repository;

use JetBrains\PhpStorm\Pure;
use TimeShow\Repository\Interfaces\CriteriaInterface;
use TimeShow\Repository\Interfaces\ExtendedRepositoryInterface;
use TimeShow\Repository\Criteria\Common\IsActive;
use TimeShow\Repository\Criteria\Common\Scopes;
use TimeShow\Repository\Criteria\Common\UseCache;
use TimeShow\Repository\Enums\CriteriaKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;

/**
 * Extends BaseRepository with extra functionality:
 *
 *      - setting default criteria to apply
 *      - active record filtering
 *      - caching (requires Rememberable or custom caching Criteria)
 *      - scopes
 */
abstract class ExtendedRepository extends BaseRepository implements ExtendedRepositoryInterface
{
    /**
     * Override if model has a basic 'active' field
     *
     * @var bool
     */
    protected bool $hasActive = false;

    /**
     * The column to check for if hasActive is true
     *
     * @var string
     */
    protected string $activeColumn = 'active';

    /**
     * Setting: enables (remember) cache
     *
     * @var bool
     */
    protected bool $enableCache = false;

    /**
     * Setting: disables the active=1 check (if hasActive is true for repo)
     *
     * @var bool
     */
    protected bool $includeInactive = false;

    /**
     * Scopes to apply to queries
     * Must be supported by model used!
     *
     * @var array
     */
    protected array $scopes = [];

    /**
     * Parameters for a given scope.
     * Note that you can only use each scope once, since parameters will be set by scope name as key.
     *
     * @var array
     */
    protected array $scopeParameters = [];



    /**
     * @param ContainerInterface  $app
     * @param Collection $initialCriteria
     */
    public function __construct(ContainerInterface $app, Collection $initialCriteria)
    {
        parent::__construct($app, $initialCriteria);

        $this->refreshSettingDependentCriteria();
    }


    // -------------------------------------------------------------------------
    //      Criteria
    // -------------------------------------------------------------------------

    /**
     * Builds the default criteria and replaces the criteria stack to apply with
     * the default collection.
     *
     * Override to also refresh the default criteria for extended functionality.
     *
     * @return void
     */
    public function restoreDefaultCriteria(): void
    {
        parent::restoreDefaultCriteria();

        $this->refreshSettingDependentCriteria();
    }

    /**
     * Refreshes named criteria, so that they reflect the current repository settings
     * (for instance for updating the Active check, when includeActive has changed)
     * This also makes sure the named criteria exist at all, if they are required and were never added.
     *
     * @return void
     */
    public function refreshSettingDependentCriteria(): void
    {
        if ($this->hasActive) {
            if (! $this->includeInactive) {
                $this->criteria->put(CriteriaKey::ACTIVE, $this->getActiveCriteriaInstance());
            } else {
                $this->criteria->forget(CriteriaKey::ACTIVE);
            }
        }

        if ($this->enableCache) {
            $this->criteria->put(CriteriaKey::CACHE, $this->getCacheCriteriaInstance());
        } else {
            $this->criteria->forget(CriteriaKey::CACHE);
        }

        if (! empty($this->scopes)) {
            $this->criteria->put(CriteriaKey::SCOPE, $this->getScopesCriteriaInstance());
        } else {
            $this->criteria->forget(CriteriaKey::SCOPE);
        }
    }


    // -------------------------------------------------------------------------
    //      Scopes
    // -------------------------------------------------------------------------

    /**
     * Adds a scope to enforce, overwrites with new parameters if it already exists
     *
     * @param  string $scope
     * @param  array  $parameters
     * @return void
     */
    public function addScope(string $scope, array $parameters = []): void
    {
        if (! in_array($scope, $this->scopes)) {
            $this->scopes[] = $scope;
        }

        $this->scopeParameters[ $scope ] = $parameters;

        $this->refreshSettingDependentCriteria();
    }

    /**
     * Adds a scope to enforce
     *
     * @param  string $scope
     * @return void
     */
    public function removeScope(string $scope): void
    {
        $this->scopes = array_diff($this->scopes, [$scope]);

        unset($this->scopeParameters[$scope]);

        $this->refreshSettingDependentCriteria();
    }

    /**
     * Clears any currently set scopes
     *
     * @return void
     */
    public function clearScopes(): void
    {
        $this->scopes          = [];
        $this->scopeParameters = [];

        $this->refreshSettingDependentCriteria();
    }



    // -------------------------------------------------------------------------
    //      Maintenance mode / settings
    // -------------------------------------------------------------------------

    /**
     * Enables maintenance mode, ignoring standard limitations on model availability
     * and disables caching (if it was enabled).
     *
     * @param bool $enable
     * @return $this
     */
    public function maintenance(bool $enable = true): static
    {
        $this->includeInactive($enable);
        $this->enableCache(! $enable);

        return $this;
    }

    /**
     * Prepares repository to include inactive entries
     * (entries with the $this->activeColumn set to false)
     *
     * @param bool $enable
     * @return $this
     */
    public function includeInactive(bool $enable = true): void
    {
        $this->includeInactive = $enable;

        $this->refreshSettingDependentCriteria();
    }

    /**
     * Prepares repository to exclude inactive entries
     *
     * @return void
     */
    public function excludeInactive(): void
    {
        $this->includeInactive(false);
    }

    /**
     * Returns whether inactive records are included
     *
     * @return bool
     */
    public function isInactiveIncluded(): bool
    {
        return $this->includeInactive;
    }

    /**
     * Enables using the cache for retrieval
     *
     * @param bool $enable
     * @return void
     */
    public function enableCache(bool $enable = true): void
    {
        $this->enableCache = $enable;

        $this->refreshSettingDependentCriteria();
    }

    /**
     * Disables using the cache for retrieval
     *
     * @return void
     */
    public function disableCache(): void
    {
        $this->enableCache(false);
    }

    /**
     * Returns whether cache is currently active
     *
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->enableCache;
    }


    // -------------------------------------------------------------------------
    //      Manipulation
    // -------------------------------------------------------------------------

    /**
     * Update the active flag for a record
     *
     * @param int|string  $id
     * @param bool $active
     * @return bool
     */
    public function activateRecord(int|string $id, bool $active = true): bool
    {
        if (! $this->hasActive) {
            return false;
        }

        $model = $this->find($id);

        if (! $model) {
            return false;
        }

        $model->{$this->activeColumn} = $active;

        return $model->save();
    }

    /**
     * Converts the tracked scopes to an array that the Scopes Common Criteria will eat.
     *
     * @return array<int, array{0: string, 1: mixed[]}>
     */
    protected function convertScopesToCriteriaArray(): array
    {
        $scopes = [];

        foreach ($this->scopes as $scope) {
            if (array_key_exists($scope, $this->scopeParameters) && ! empty($this->scopeParameters[ $scope ])) {
                $scopes[] = [$scope, $this->scopeParameters[ $scope ]];
                continue;
            }

            $scopes[] = [$scope, []];
        }

        return $scopes;
    }

    /**
     * Returns Criteria to use for is-active check.
     *
     * @return IsActive CriteriaInterface
     */
    protected function getActiveCriteriaInstance(): CriteriaInterface
    {
        return new IsActive($this->activeColumn);
    }

    /**
     * Returns Criteria to use for caching. Override to replace with something other
     * than Rememberable (which is used by the default Common\UseCache Criteria);
     *
     * @return CriteriaInterface<TModel, Model>
     */
    protected function getCacheCriteriaInstance(): CriteriaInterface
    {
        return new UseCache();
    }

    /**
     * Returns Criteria to use for applying scopes. Override to replace with something
     * other the default Common\Scopes Criteria.
     *
     * @return CriteriaInterface<TModel, Model>
     */
    protected function getScopesCriteriaInstance(): CriteriaInterface
    {
        return new Scopes(
            $this->convertScopesToCriteriaArray()
        );
    }

}