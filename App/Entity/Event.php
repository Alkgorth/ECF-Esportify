<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeImmutable;

enum Visibility: string
{
    case Public = 'public';
    case Privé = 'privé';
}

enum Status: string
{
    case EnAttente = 'en attente';
    case Validé = 'validé';
    case Refusé = 'refusé';
    case Annulé = 'annulé';
}

class Event extends MainEntity
{
    protected ?int $id_event = null;
    protected string $name_event = '';
    protected string $name_game = '';
    protected DateTimeImmutable $date_hour_start;
    protected DateTimeImmutable $date_hour_end;
    protected int $nombre_de_joueurs;
    protected string $description = '';
    protected Visibility $visibility;
    protected Status $status;
    protected int $fk_id_user;
    protected ?int $fk_id_plateforme = null;
    protected string $cover_image_path;

    /**
     * Get the value of id_event
     */
    public function getIdEvent(): ?int
    {
        return $this->id_event;
    }

    /**
     * Set the value of id_event
     */
    public function setIdEvent(?int $id_event): self
    {
        $this->id_event = $id_event;

        return $this;
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
     * Get the value of name_game
     */
    public function getNameGame(): string
    {
        return $this->name_game;
    }

    /**
     * Set the value of name_game
     */
    public function setNameGame(string $name_game): self
    {
        $this->name_game = $name_game;

        return $this;
    }

    /**
     * Get the value of date_hour_start
     */
    public function getDateHourStart(): DateTimeImmutable
    {
        return $this->date_hour_start;
    }

    /**
     * Set the value of date_hour_start
     */
    public function setDateHourStart(DateTimeImmutable $date_hour_start): self
    {
        $this->date_hour_start = $date_hour_start;

        return $this;
    }

    /**
     * Get the value of date_hour_end
     */
    public function getDateHourEnd(): DateTimeImmutable
    {
        return $this->date_hour_end;
    }

    /**
     * Set the value of date_hour_end
     */
    public function setDateHourEnd(DateTimeImmutable $date_hour_end): self
    {
        $this->date_hour_end = $date_hour_end;

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
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of visibility
     */
    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    /**
     * Set the value of visibility
     */
    public function setVisibility(Visibility $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of fk_id_user
     */
    public function getFkIdUser(): int
    {
        return $this->fk_id_user;
    }

    /**
     * Set the value of fk_id_user
     */
    public function setFkIdUser(int $fk_id_user): self
    {
        $this->fk_id_user = $fk_id_user;

        return $this;
    }

    /**
     * Get the value of fk_id_plateforme
     */
    public function getFkIdPlateforme(): ?int
    {
        return $this->fk_id_plateforme;
    }

    /**
     * Set the value of fk_id_plateforme
     */
    public function setFkIdPlateforme(?int $fk_id_plateforme): self
    {
        $this->fk_id_plateforme = $fk_id_plateforme;

        return $this;
    }

        /**
     * Get the value of cover_image_path
     */
    public function getCoverImagePath(): string
    {
        return $this->cover_image_path;
    }

    /**
     * Set the value of cover_image_path
     */
    public function setCoverImagePath(string $cover_image_path): self
    {
        $this->cover_image_path = $cover_image_path;

        return $this;
    }
}
