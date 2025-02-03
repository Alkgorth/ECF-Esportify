<?php
declare(strict_types=1);

namespace App\Entity;

class Scores extends MainEntity
{
    protected int $fk_id_user;
    protected int $fk_id_event;
    protected int $score;
    protected float $score_total;

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

    /**
     * Get the value of score
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * Set the value of score
     */
    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get the value of score_total
     */
    public function getScoreTotal(): float
    {
        return $this->score_total;
    }

    /**
     * Set the value of score_total
     */
    public function setScoreTotal(float $score_total): self
    {
        $this->score_total = $score_total;

        return $this;
    }
}