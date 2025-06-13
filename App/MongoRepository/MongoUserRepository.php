<?php

namespace App\MongoRepository;

use App\MongoEntity\UserDocument;
use MongoDB\Collection;

class MongoUserRepository extends MongoMainRepository
{
    public function addEventToUser(array $userData, array $eventData): void
    {
        $this->getCollection('Users')->updateOne(
            ['id' => $userData['id']],
            ['pseudo' => $userData['pseudo']],
            ['$addToSet' => ['events' => $eventData['name']]],
            ['upsert' => true]
        );
    }
}