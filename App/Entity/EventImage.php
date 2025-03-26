<?php
declare(strict_types=1);

namespace App\Entity;

class EventImage extends MainEntity
{
    protected ?int $id_event_image = null;
    protected int $fk_id_event;
    protected string $image_path;
    protected string $image_order;

    

    /**
     * Get the value of id_event_image
     */
    public function getIdEventImage(): ?int
    {
        return $this->id_event_image;
    }

    /**
     * Set the value of id_event_image
     */
    public function setIdEventImage(?int $id_event_image): self
    {
        $this->id_event_image = $id_event_image;

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
     * Get the value of image_path
     */
    public function getImagePath(): string
    {
        return $this->image_path;
    }

    /**
     * Set the value of image_path
     */
    public function setImagePath(string $image_path): self
    {
        $this->image_path = $image_path;

        return $this;
    }

    /**
     * Get the value of image_order
     */
    public function getImageOrder(): string
    {
        return $this->image_order;
    }

    /**
     * Set the value of image_order
     */
    public function setImageOrder(string $image_order): self
    {
        $this->image_order = $image_order;

        return $this;
    }
}