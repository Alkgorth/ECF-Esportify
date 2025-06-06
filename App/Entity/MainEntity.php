<?php

namespace App\Entity;

use App\Tools\StringTools;

class MainEntity
{
    public static function createAndHydrate(array $data): static
    {
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }

    public function hydrate(array $data)
    {
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $method = 'set' . StringTools::toPascalCase($key);
                if (method_exists($this, $method)) {
                    if ($key == 'creation_date') {
                        $value = new \DateTime($value);
                    }
                    if ($key == 'expiration_date') {
                        $value = new \DateTime($value);
                    }
                    if ($key == 'date_hour_start') {
                        $value = new \DateTimeImmutable($value);
                    }
                    if ($key == 'date_hour_end') {
                        $value = new \DateTimeImmutable($value);
                    }
                    if ($key == 'visibility') {
                        $value = Visibility::tryFrom($value);
                    }
                    $this->$method($value);
                }
            }
        }
    }
}
