<?php
namespace TimeShow\Repository\Data;

use Illuminate\Http\Request;

/**
 * @template TKey of array-key
 * @template-covariant TValue
 */
class Input extends Request
{
    public function __construct(array $query = [])
    {
        parent::__construct($query);
    }
}
