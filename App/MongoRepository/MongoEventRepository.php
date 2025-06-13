<?php

namespace App\MongoRepository;

use App\MongoEntity\EventDocument;
use MongoDB\Collection;

class MongoEventRepository extends MongoMainRepository
{
    public function addUserToEvent(array $eventData, array $userData): void
    {
        $this->getCollection('Events')->updateOne(
            ['id' => $eventData['id']],
            ['name' => $eventData['name']],
            ['start' => $eventData['start']],
            ['end' => $eventData['end']],
            ['joueurs' => $eventData['joueurs']],
            ['status' => $eventData['status']],
            // ['$addToSet' => ['joueurs' => $userData]],
            ['joueurs' => $userData['pseudo']],
            ['upsert' => true]
        );
    }
}