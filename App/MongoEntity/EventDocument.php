<?php

namespace App\MongoEntity;

class EventDocument extends MongoMainEntity
{
    private string $id = '';
    private string $name_event = '';
    private int $nombre_de_joueurs;
    private string $date_start = '';
    private string $date_end = '';
    private string $status = '';
    private array $joueurs = [];

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the value of name_event
     */
    public function getNameEvent(): string
    {
        return $this->name_event;
    }

    /**
     * Set the value of name_event
     */
    public function setNameEvent(string $name_event): self
    {
        $this->name_event = $name_event;

        return $this;
    }

    /**
     * Get the value of nombre_de_joueurs
     */
    public function getNombreDeJoueurs(): int
    {
        return $this->nombre_de_joueurs;
    }

    /**
     * Set the value of nombre_de_joueurs
     */
    public function setNombreDeJoueurs(int $nombre_de_joueurs): self
    {
        $this->nombre_de_joueurs = $nombre_de_joueurs;

        return $this;
    }

    /**
     * Get the value of date_start
     */
    public function getDateStart(): string
    {
        return $this->date_start;
    }

    /**
     * Set the value of date_start
     */
    public function setDateStart(string $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    /**
     * Get the value of date_end
     */
    public function getDateEnd(): string
    {
        return $this->date_end;
    }

    /**
     * Set the value of date_end
     */
    public function setDateEnd(string $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of joueurs
     */
    public function getJoueurs(): array
    {
        return $this->joueurs;
    }

    /**
     * Set the value of joueurs
     */
    public function setJoueurs(array $joueurs): self
    {
        $this->joueurs = $joueurs;

        return $this;
    }
}