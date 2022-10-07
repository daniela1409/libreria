<?php

namespace App\Repository;

use App\Entity\ItemSale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemSale>
 *
 * @method ItemSale|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemSale|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemSale[]    findAll()
 * @method ItemSale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemSaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemSale::class);
    }

    public function save(ItemSale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ItemSale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ItemSale[] Returns an array of ItemSale objects
    */
   public function findItemSale($sale): array
   {
        
       return $this->createQueryBuilder('i')
           ->select("b.name, b.quantity") 
           ->join('i.book', 'b') 
           ->join('i.sale', 's')
           ->join('s.user', 'us')
           ->andWhere('s.id = :val')
           ->setParameter('val', $sale->getId())
           ->getQuery()
           ->getResult()
       ;
   }

}
