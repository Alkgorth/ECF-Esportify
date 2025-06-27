<?php

namespace App\MongoRepository;

use App\MongoEntity\FavoriteDocument;
use MongoDB\Collection;

class FavoriteDocumentRepository extends MongoMainRepository
{
    protected function getFavoriteCollection(): Collection
    {
        return $this->getCollection('Subscribe');
    }

    public function findUserByName(string $name): ?FavoriteDocument
    {
        $result = $this->getFavoriteCollection()->find();

        if ($result) {
            foreach ($result as $document) {
                var_dump($document->nom);
            }
        }
        die;
        return FavoriteDocument::createAndHydrate($result);
    }

    public function findAll(): array
    {
        $documents = $this->getFavoriteCollection()->find();
        $entities = [];

        foreach ($documents as $doc) {
            $entities[] = FavoriteDocument::createAndHydrate($doc);
        }
        return $entities;
    }
}