<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HouseRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Le titre {{ value }} est déjà utilisé pour un bien existant."
 * )
 */
#[ORM\Entity(repositoryClass: HouseRepository::class)]
class House
{
    const HEAT = [
        0 => 'Electrique',
        1 => 'Gaz'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Le titre doit comporter au minimum {{ limit }} caractères",
     *      maxMessage = "Le titre ne peut comporter plus de {{ limit }} caractères"
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    /**
     * @Assert\Range(
     *      min = 10,
     *      max = 400,
     *      notInRangeMessage = "Entrez une valeur entre {{ min }} et {{ max }}m²"
     * )
     */
    #[ORM\Column(type: 'integer')]
    private $surface;

    /**
     * @Assert\Positive
     */
    #[ORM\Column(type: 'integer')]
    private $rooms;

    /**
     * @Assert\PositiveOrZero
     */
    #[ORM\Column(type: 'integer')]
    private $bedrooms;

    /**
     * @Assert\PositiveOrZero
     */
    #[ORM\Column(type: 'integer')]
    private $floor;

    /**
     * @Assert\PositiveOrZero
     */
    #[ORM\Column(type: 'integer')]
    private $price;

    /**
     * @Assert\Choice({0, 1})
     */
    #[ORM\Column(type: 'integer')]
    private $heat;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "La ville doit comporter au minimum {{ limit }} caractères",
     *      maxMessage = "La ville ne peut comporter plus de {{ limit }} caractères"
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "L'adresse doit comporter au minimum {{ limit }} caractères",
     *      maxMessage = "L'adresse ne peut comporter plus de {{ limit }} caractères"
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    /**
     * @Assert\Regex("/^[0-9]{5}$/")
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $postal_code;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    #[ORM\Column(type: 'boolean')]
    private $sold = false;

    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'houses')]
    private $options;



    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 800,
     *     minHeight = 100,
     *     maxHeight = 800,
    *      mimeTypes = "image/jpeg",
    *      mimeTypesMessage = "L'image doit être au format jpeg"
     * )
     * @Vich\UploadableField(mapping="img_house", fileNameProperty="imageName", size="imageSize")
     * 
     * @var File|null
     */
    #[Vich\UploadableField(mapping: 'img_house', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;





    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->options = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug():string
    {
        return (new Slugify())->slugify($this->title); 
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceFormatted(): ?string
    {
        return number_format($this->price, 0, '', ' ').' €';
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getHeatType():string 
    {
        return self::HEAT[$this->heat];
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
    public function getCreatedAtFormatted(): ?string
    {
        return $this->created_at->format('d/m/Y à H\hi\m\i\n');
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addHouse($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeHouse($this);
        }

        return $this;
    }

    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
}
