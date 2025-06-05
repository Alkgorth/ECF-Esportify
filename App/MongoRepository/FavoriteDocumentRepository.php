<?php

namespace App\MongoRepository;

use App\MongoEntity\FavoriteDocument;
use MongoDB\Collection;

class FavoriteDocumentRepository extends MongoMainRepository
{
    protected function getFavoriteCollection(): Collection
    {
        return $this->getCollection('favorite');
    }

    public function findUserById(string $idUser): ?FavoriteDocument
    {
        $result = $this->getFavoriteCollection()->findOne([
            'id_user' => $idUser
        ]);

        if (!$result) {
            return null;
            }

        return FavoriteDocument::createAndHydrate($result->getArrayCopy());
    }

    public function findAll(): array
    {
        $documents = $this->getFavoriteCollection()->find();
        $entities = [];

        foreach ($documents as $doc) {
            $entities[] = FavoriteDocument::createAndHydrate($doc->getArrayCopy());
        }

        return $entities;
    }
}