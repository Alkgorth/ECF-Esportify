<?php

namespace App\Db;

require __DIR__ . '/../../vendor/autoload.php';
use MongoDB\Client;
use MongoDB\Database;

class MongoConnector
{
    private $mongo_host;
    private $mongo_port;
    private $mongo_name;
    private $mongo_user;
    private $mongo_password;
    private $mongo_client = null;
    private static $_instance = null;

    private function __construct()
    {
        $conf = require_once __DIR__ . '/../../mongoConfig.php';
        
        if (isset($conf['mongo_name'])) {
            $this->mongo_name = $conf['mongo_name'];
        } else {
            $this->mongo_name = 'test';
        }

        if (isset($conf['mongo_user'])) {
            $this->mongo_user = $conf['mongo_user'];
        } else {
            $this->mongo_user = null;
        }

        if (isset($conf['mongo_password'])) {
            $this->mongo_password = $conf['mongo_password'];
        } else {
            $this->mongo_password = null;
        }

        if (isset($conf['mongo_port'])) {
            $this->mongo_port = $conf['mongo_port'];
        } else {
            $this->mongo_port = 27017;
        }

        if (isset($conf['mongo_host'])) {
            $this->mongo_host = $conf['mongo_host'];
        } else {
            $this->mongo_host = 'localhost';
        }
    }

        public static function getInstance(): self
    {

        if (is_null(self::$_instance)) {

            self::$_instance = new MongoConnector();
        }
        return self::$_instance;
    }

    public function getClient(): Client
    {
        if(is_null($this->mongo_client)) {

            if (!empty($this->mongo_user) && !empty($this->mongo_password)) {

                $uri = "mongodb://{$this->mongo_user}:{$this->mongo_password}@{$this->mongo_host}:{$this->mongo_port}";

            } else {
                $uri = "mongodb://{$this->mongo_host}:{$this->mongo_port}";
            }
            $this->mongo_client = new Client($uri);
        }

        return $this->mongo_client;
    }

    public function getDb(): Database
    {
        return $this->getClient()->selectDatabase($this->mongo_name);
    }
}