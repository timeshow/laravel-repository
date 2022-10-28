<?php
declare(strict_types=1);
namespace TimeShow\Repository\Enums;

use MyCLabs\Enum\Enum;

/**
 * Unique identifiers for standard Criteria that may be loaded in repositories.
 *
 * @method static static ACTIVE()
 * @method static static CACHE()
 * @method static static ORDER()
 * @method static static SCOPE()
 * @method static static WITH()
 *
 * @extends Enum<string>
 */
class CriteriaKey extends Enum
{
    const ACTIVE = 'active';    // whether to check for 'active' = 1
    const CACHE  = 'cache';     // for rememberable()
    const ORDER  = 'order';     // for order by (multiple in one optionally)
    const SCOPE  = 'scope';     // for scopes applied (multiple in one optionally)
    const WITH   = 'with';      // for eager loading
}