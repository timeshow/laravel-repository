<?php
namespace TimeShow\Repository\Helpers;

use League\Fractal\Serializer\ArraySerializer;

class ArraySerializerHelper extends ArraySerializer
{
    public function collection(?string $resourceKey, array $data): array
    {
        if (empty($resourceKey)) {
            return $data;
        }

        return [$resourceKey => $data];
    }
}