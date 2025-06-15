<?php

namespace App\MongoRepository;

use App\MongoEntity\EventDocument;
use MongoDB\Collection;
use Exception;

class MongoEventRepository extends MongoMainRepository
{
    public function addUserToEvent(array $eventData, array $userData)
    {
        try {
            $options = ['upsert' => true];
            $filter = ['id' => $eventData['id']];
            $update = [
                '$setOnInsert' => [
                    'name' => $eventData['name_event'],
                    'start' => $eventData['date_start'],
                    'end' => $eventData['date_end'],
                    'joueurs' => $eventData['nombre_de_joueurs'],
                    'status' => $eventData['status']
                ],
                '$addToSet' => [
                    'participants' => [
                        'id_user' => $userData['id'],
                        'pseudo' => $userData['pseudo']
                    ]
                ]
            ];

            $updateResult = $this->getCollection('Events')->updateOne(
                $filter,
                $update,
                $options
            );

            return $updateResult->getModifiedCount() > 0 || $updateResult->getUpsertedId() !== null;

        } catch (\Exception $e) {
            error_log("Error Mongo addUserToEvent" . $e->getMessage());
            return false;
        }
    }
}