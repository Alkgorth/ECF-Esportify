<?php
declare(strict_types=1);

namespace App\Entity;

class Password extends MainEntity
{
    protected int $fk_id_user;
    protected int $fk_id_token;

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
     * Get the value of fk_id_token
     */
    public function getFkIdToken(): int
    {
        return $this->fk_id_token;
    }

    /**
     * Set the value of fk_id_token
     */
    public function setFkIdToken(int $fk_id_token): self
    {
        $this->fk_id_token = $fk_id_token;

        return $this;
    }
}