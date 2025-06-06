<?php

namespace App\MongoRepository;

use App\Db\MongoConnector;
use MongoDB\Database;

class MongoMainRepository 
{
    protected Database $db;

    public function __construct()
    {
        $mongo = MongoConnector::getInstance('esportify_mongo');
        $this->db = $mongo->getDb();
    }

    protected function getCollection(string $name): \MongoDB\Collection
    {
        return $this->db->selectCollection($name);
    }
}