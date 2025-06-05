<?php

namespace App\MongoEntity;

use App\Tools\StringTools;
use MongoDB\BSON\ObjectId;


class MongoMainEntity
{
    public static function createAndHydrate(array $data): static
    {
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }

    public function hydrate(array $data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $method = 'set' . StringTools::toPascalCase($key);
                if (method_exists($this, $method)) {
                    if ($key === '_id') {
                        $value = (string) $value;
                    }
                    $this->$method($value);
                }
            }    
        }
    }
}