<?php

namespace App\MongoRepository;

use App\MongoEntity\UserDocument;
use MongoDB\Collection;
use Exception;

class MongoUserRepository extends MongoMainRepository
{
    public function addEventToUser(array $userData, array $eventData)
    {
        try {
            $options = ['upsert' => true];
            $filter = ['id' => $userData['id']];
            $update = [
                '$setOnInsert' => [
                    'pseudo' => $userData['pseudo'],
                ],
                '$addToSet' => [
                    'events' => [
                        'name_event' => $eventData['name_event']
                    ]
                ]
            ];
            
            $updateResult = $this->getCollection('Users')->updateOne(
                $filter,
                $update,
                $options
            );            
            
            return $updateResult->getModifiedCount() > 0 || $updateResult->getUpsertedId() !== null;

        } catch (\Exception $e) {
            error_log("Error Mongo addEventToUser" . $e->getMessage());
            return false;
        }
    }
}