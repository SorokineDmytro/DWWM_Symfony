<?php

namespace App\Repository;

use App\Entity\Mouvement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mouvement>
 */
class MouvementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mouvement::class);
    }

    public function searchFetch($mot){
        $qb=$this->createQueryBuilder("m");
        $qb->join("m.typeMouvement","tm");
        $qb->join("m.tiers","ti");
        $qb->select("m.id,m.numMouvement,m.dateMouvement,ti.nomTiers,tm.prefixe,tm.libelle");
        $qb->where("m.numMouvement like :mot or ti.nomTiers like :mot or tm.prefixe like :mot or tm.libelle like :mot");
        $qb->setParameter("mot","%$mot%");
        $qb->orderBy("m.id","desc");
        return $qb->getQuery()->getArrayResult();
    } 

    //    /**
    //     * @return Mouvement[] Returns an array of Mouvement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Mouvement
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
