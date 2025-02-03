<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeImmutable;

class Token extends MainEntity
{
    protected ?int $id_token = null;
    protected DateTimeImmutable $creation_date;
    protected DateTimeImmutable $expiration_date;
    protected string $token = '';

    /**
     * Get the value of id_token
     */
    public function getIdToken(): ?int
    {
        return $this->id_token;
    }

    /**
     * Set the value of id_token
     */
    public function setIdToken(?int $id_token): self
    {
        $this->id_token = $id_token;

        return $this;
    }

    /**
     * Get the value of creation_date
     */
    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creation_date;
    }

    /**
     * Set the value of creation_date
     */
    public function setCreationDate(DateTimeImmutable $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * Get the value of expiration_date
     */
    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expiration_date;
    }

    /**
     * Set the value of expiration_date
     */
    public function setExpirationDate(DateTimeImmutable $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the value of token
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}