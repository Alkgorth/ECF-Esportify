<?php

namespace App\MongoRepository;

use App\MongoEntity\EventDocument;
use Exception;
use MongoDB\Collection;

class MongoEventRepository extends MongoMainRepository
{
    public function addUserToEvent(array $eventData, string $userData)
    {
        try {
            $result = $this->getCollection('Events')->insertOne(
            ['id' => $eventData['id']],
            [
                '$set' => [
                    'name' => $eventData['name'],
                    'start' => $eventData['start'],
                    'end' => $eventData['end'],
                    'joueurs' => $eventData['joueurs'],
                    'status' => $eventData['status'],
                ],
                '$addToSet' => ['joueurs' => $userData
                ]
            ],
            ['upsert' => true]
        );
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur MongoEventRepository::addUserToEvent : " . $e->getMessage());
            throw new Exception("Erreur lors de l'ajout du joueur à l'évènement : " . $e->getMessage());
        }
    }
}