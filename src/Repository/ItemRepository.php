<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use App\Entity\Lists;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function add(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function increasePosition(Lists $list):void
    {
        $this->createQueryBuilder('i')
        ->update($this->getEntityName(),'i')
        ->set('i.position', 'i.position + 1')
        ->where('i.list = :list')
        ->setParameter('list',$list)
        ->getQuery()
        ->getResult();
    }

    public function getMaxPostion(Lists $list): array
    {
        return $this->createQueryBuilder('i')
        ->select("MAX(i.position) as max_position")
        ->where('i.list = :list')
        ->setParameter('list', $list)
        ->getQuery()
        ->getResult();
    }

    public function findItemsByList($list): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.list = :val')
            ->orderBy('i.position', 'ASC')
            ->setParameter('val', $list)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
