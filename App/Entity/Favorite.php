<?php
declare(strict_types=1);

namespace App\Entity;

class Favorite extends MainEntity
{
    protected ?int $id_favorite = null;
    protected int $fk_id_user;
    protected int $fk_id_event;


    /**
     * Get the value of id_favorite
     */
    public function getIdFavorite(): ?int
    {
        return $this->id_favorite;
    }

    /**
     * Set the value of id_favorite
     */
    public function setIdFavorite(?int $id_favorite): self
    {
        $this->id_favorite = $id_favorite;

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
     * Get the value of fk_id_event
     */
    public function getFkIdEvent(): int
    {
        return $this->fk_id_event;
    }

    /**
     * Set the value of fk_id_event
     */
    public function setFkIdEvent(int $fk_id_event): self
    {
        $this->fk_id_event = $fk_id_event;

        return $this;
    }
}