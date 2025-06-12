<?php

namespace App\MongoEntity;

class UserDocument extends MongoMainEntity
{
    private string $id = '';
    private string $pseudo = '';
    private array $events = [];

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     */
    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of events
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Set the value of events
     */
    public function setEvents(array $events): self
    {
        $this->events = $events;

        return $this;
    }
}