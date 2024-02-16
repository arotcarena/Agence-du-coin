<?php

namespace App\DataFixtures;

use App\Entity\House;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class HouseFixtures extends Fixture
{
    private $way;

    private $city;

    private $postal_code;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Faker\Provider\fr_FR\Address($faker));

        for ($i=1; $i <= 100; $i++) 
        { 
            $this->format_address($faker->address());

            $house = new House();
            $house->setTitle($faker->sentence(random_int(1, 5)))
                    ->setDescription($faker->paragraphs(random_int(1, 4), true))
                    ->setSurface(random_int(2, 35) * 10)
                    ->setRooms(random_int(1, 10))
                    ->setBedrooms(random_int(1, 7))
                    ->setFloor(random_int(0, 8))
                    ->setPrice(random_int(30, 150) * 1000)
                    ->setHeat(random_int(0, 1))
                    ->setCity($this->city)
                    ->setAddress($this->way)
                    ->setPostalCode($this->postal_code);

            $manager->persist($house);
        }
        $manager->flush();
    }

    private function format_address(string $address):void 
    {
        $city_pos = strripos($address, ' ');
        $city = trim(substr($address, $city_pos), ' ');
        $way_postal_code = str_replace(' '.$city, '', $address);
        $postal_code_pos = strripos($way_postal_code, '\\n');
        $postal_code = substr($way_postal_code, -6);
        $this->way = str_replace($postal_code, '', $way_postal_code); trim($way_postal_code, $postal_code);
        $this->postal_code = str_replace(' ', '', $postal_code);
        $this->city = $city;
    }
    
}
