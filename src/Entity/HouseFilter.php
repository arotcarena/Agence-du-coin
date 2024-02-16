<?php

namespace App\Entity;

use App\Entity\Option;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


class HouseFilter
{
    /**
     * @Assert\PositiveOrZero
     * @var int|null
     */
    private $max_price;

    /**
     * @Assert\PositiveOrZero
     * @var int|null
     */
    private $min_price;

    /**
     * @Assert\PositiveOrZero
     * @var int|null
     */
    private $min_rooms;

    /**
     * @Assert\Range(
     *      min = 10,
     *      max = 400,
     *      notInRangeMessage = "Entrez une surface entre {{ min }} et {{ max }}m²",
     * )
     * @var int|null
     */
    private $min_surface;

    /**
     * @var ArrayCollection
     */
    private $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxPrice(): ?int
    {
        return $this->max_price;
    }

    public function setMaxPrice(?int $max_price): self
    {
        $this->max_price = $max_price;

        return $this;
    }

    public function getMinPrice(): ?int
    {
        return $this->min_price;
    }

    public function setMinPrice(?int $min_price): self
    {
        $this->min_price = $min_price;

        return $this;
    }

    public function getMinRooms(): ?int
    {
        return $this->min_rooms;
    }

    public function setMinRooms(?int $min_rooms): self
    {
        $this->min_rooms = $min_rooms;

        return $this;
    }

    public function getMinSurface(): ?int
    {
        return $this->min_surface;
    }

    public function setMinSurface(?int $min_surface): self
    {
        $this->min_surface = $min_surface;

        return $this;
    }


    
    public function getOptions():ArrayCollection
    {
        return $this->options;
    }

    public function setOptions(ArrayCollection $options):self
    {
        $this->options = $options;

        return $this;
    }
}
