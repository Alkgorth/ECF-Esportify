<?php

namespace App\MongoEntity;

class FavoriteDocument extends MongoMainEntity
{
    private $id_user;
    private $events = [];

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser(string $id_user): void
    {
        $this->id_user = $id_user;

    }

        public function getEvents(): array
    {
        return $this->events;
    }

    public function setEvents(array $events): void
    {
        $this->events = $events;

    }

}
