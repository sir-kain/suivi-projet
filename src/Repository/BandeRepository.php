<?php

namespace App\Repository;

use App\Entity\Bande;
use App\Entity\Depense;
use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bande>
 *
 * @method Bande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bande[]    findAll()
 * @method Bande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bande::class);
    }

    public function save(Bande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest()
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.date_debut', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function remove(Bande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function totalDepense(Bande $entity): int
    {
        return array_reduce($entity->getDepenses()->toArray(), function ($carry, $item) {
            /** @var Depense $item */
            return $carry + $item->getPrix();
        }, 0);
    }

    public function totalVente(Bande $entity): int
    {
        return array_reduce($entity->getVentes()->toArray(), function ($carry, $item) {
            /** @var Vente $item */
            return $carry + $item->getPrix();
        }, 0);
    }

    public function bilan($entity): float
    {
        return $this->totalVente($entity) - $this->totalDepense($entity);
    }

    public function stock(Bande $entity): int
    {
        $quantiteTotalVente = array_reduce($entity->getVentes()->toArray(), function ($carry, $item) {
            /** @var Vente $item */
            return $carry + $item->getQuantite();
        }, 0);
        return $entity->getNbPoussins() - ($entity->getNbMortalite() + $quantiteTotalVente);
    }

    //    /**
    //     * @return Bande[] Returns an array of Bande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bande
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
