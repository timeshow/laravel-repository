<?php

namespace TimeShow\Repository\Helpers;

use Illuminate\Database\Eloquent\Model;

trait TransformerHelper
{
    public function getTimestamps($item, array $dates = []): array
    {
        if (! $item instanceof Model) {
            return [];
        }
        $result = [];
        $dates = [$item->getCreatedAtColumn(), $item->getUpdatedAtColumn(), ...$dates];
        foreach ($dates as $field) {
            if (is_null($field)) {
                continue;
            }
            $date = $item->getAttribute($field);
            if ($date instanceof \DateTimeInterface) {
                $result[$field] = $date->format('Y-m-d H:i:s');
            }
        }

        return $result;
    }

    /**
     * @param  Model  $item
     */
    public function pluckAttributes($item, array $keys = []): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $item->getAttribute($key);
        }

        return $result;
    }
}
