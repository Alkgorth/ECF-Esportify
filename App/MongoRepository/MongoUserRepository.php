<?php

namespace App\MongoRepository;

use App\MongoEntity\UserDocument;
use MongoDB\Collection;

class MongoUserRepository extends MongoMainRepository
{
    public function inscriptionEvent(string $userId, array $eventData): void
    {
        $this->getCollection('User')->updateOne(
            ['id' => $userId],
            // ['$addToSet' => ['events' => $eventData]],
            ['events' => $eventData],
            ['upsert' => true]
        );
    }
}