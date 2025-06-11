<?php

namespace App\MongoRepository;

use App\MongoEntity\EventDocument;
use MongoDB\Collection;

class MongoEventRepository extends MongoMainRepository
{
    public function addUserEvent(string $eventId, array $userData): void
    {
        $this->getCollection('Events')->updateOne(
            ['id' => $eventId],
            // ['$addToSet' => ['joueurs' => $userData]],
            ['joueurs' => $userData],
            ['upsert' => true]
        );
    }
}