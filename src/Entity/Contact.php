<?php
namespace App\Entity;

use App\Entity\House;
use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @var string|null
     * @Assert\Email(
     *     message = "L'adresse {{ value }} n'est pas une adresse e-mail valide."
     * )
     */
    private $email;

    /**
     * @var string|null
     * 
     */
    private $phone;

    /**
     * @var string|null
     * @Assert\Length(
     *      min = 10,
     *      minMessage = "Votre message doit comporter au moins {{ limit }} caractères."
     * )
     */
    private $message;

    /**
     * @var House|null
     */
    private $house;


    

    /**
     * Get the value of house
     *
     * @return  House|null
     */ 
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set the value of house
     *
     * @param  House|null  $house
     *
     * @return  self
     */ 
    public function setHouse($house)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return  string|null
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param  string|null  $message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get pattern="[0-9]{10}",
     *
     * @return  string|null
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set pattern="[0-9]{10}",
     *
     * @param  string|null  $phone  pattern="[0-9]{10}",
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of firstName
     *
     * @return  string|null
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @param  string|null  $firstName
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     *
     * @return  string|null
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @param  string|null  $lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get message = "L'adresse '{{ value }}' n'est pas une adresse e-mail valide."
     *
     * @return  string|null
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set message = "L'adresse '{{ value }}' n'est pas une adresse e-mail valide."
     *
     * @param  string|null  $email  message = "L'adresse '{{ value }}' n'est pas une adresse e-mail valide."
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}