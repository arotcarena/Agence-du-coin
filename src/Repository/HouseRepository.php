<?php

namespace App\Repository;

use App\Entity\House;
use Doctrine\ORM\Query;
use App\Entity\HouseFilter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method House|null find($id, $lockMode = null, $lockVersion = null)
 * @method House|null findOneBy(array $criteria, array $orderBy = null)
 * @method House[]    findAll()
 * @method House[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, House::class);
    }


    
    public function findAllNotSoldQuery(?HouseFilter $houseFilter = null):Query              
    {                                             // on peut faire aussi comme ça :  $query = $this->em->createQuery("SELECT * FROM house WHERE sold = ");
        $queryBuilder = $this->findNotSold();
        if(!empty($houseFilter))
        {
            $queryBuilder = $this->filter($houseFilter, $queryBuilder);
        }
        return $queryBuilder->getQuery();
    }
    /**
     * @return House[]
     */
    public function findLastsNotSold():array
    {
        return $this->findNotSold()
            ->orderBy('h.created_at', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }



    private function findNotSold():QueryBuilder
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.sold = :sold')
            ->setParameter('sold', false);
    }
    /**
     * A MODIFIER EN UTILISANT get_class_method() pour faire une boucle sur tous les getters et si il est pas nul on l'utilise
     */
    private function filter(HouseFilter $houseFilter, ?QueryBuilder $queryBuilder = null):QueryBuilder
    {
        $queryBuilder = $queryBuilder ?: $this->createQueryBuilder('h');

        foreach(['max_price', 'min_price', 'min_rooms', 'min_surface'] as $key)
        {
            $getter_name = $this->getter_name($key);
            if($houseFilter->$getter_name() !== null)
            {
                
                if(str_contains($key, 'min_'))
                {
                    $field = str_replace('min_', '', $key);
                    $operator = '>=';
                }
                elseif(str_contains($key, 'max_'))
                {
                    $field = str_replace('max_', '', $key);
                    $operator = '<=';
                }
                else
                {
                    $field = $key;
                    $operator = '=';
                }
                $queryBuilder->andWhere('h.'.$field.' '.$operator.' :'.$key)
                            ->setParameter($key, $houseFilter->$getter_name());            
            }
        }
        if($houseFilter->getOptions()->count() > 0)
        {
            $i = 0;
            foreach($houseFilter->getOptions() as $option)
            {
                $i++;
                $queryBuilder->andWhere(':option'.$i.' MEMBER OF h.options')
                            ->setParameter('option'.$i, $option);
            }
        }
        return $queryBuilder;
    }
    private function getter_name(string $key):string 
    {
        if(strpos($key, '_'))
        {
            $parts = explode('_', $key);
            return 'get' . ucfirst($parts[0]) . ucfirst($parts[1]);
        }
        return 'get' . ucfirst($key);
    }
    


    /*
    /**
     * @return House[] Returns an array of House objects
    */
/* 
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findOneBySomeField($value): ?House
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
*/
}
