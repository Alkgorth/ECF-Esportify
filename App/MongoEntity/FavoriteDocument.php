<?php

namespace App\MongoEntity;

class FavoriteDocument extends MongoMainEntity
{
    private string $id_user = '';
    private string $events = '';

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser(string $id_user): void
    {
        $this->id_user = $id_user;
    }

        public function getEvents(): string
    {
        return $this->events;
    }

    public function setEvents(string $events): void
    {
        $this->events = $events;
    }

}
