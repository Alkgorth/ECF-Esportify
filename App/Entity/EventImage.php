<?php
declare(strict_types=1);

namespace App\Entity;

class EventImage extends MainEntity
{
    protected ?int $id_event_image = null;
    protected int $fk_id_event;
    protected string $image_path;
    protected string $image_order;

    
}