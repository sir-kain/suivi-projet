<?php

namespace App\Repository;

use App\Entity\Bande;
use App\Entity\Compte;
use App\Entity\Depense;
use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bande>
 *
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

    public function find($id, $lockMode = null, $lockVersion = null): Bande|null
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.depenses', 'd')
            ->leftJoin('b.ventes', 'v')
            ->addSelect('d, v')
            ->where('b.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findLatestNotCloture()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.date_cloture IS NULL')
            ->orderBy('p.date_debut', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getBandes()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p, d, v, CASE WHEN p.date_cloture IS NULL THEN 1 ELSE 0 END AS is_open')
            ->orderBy('p.date_debut', 'DESC')
            ->leftJoin('p.ventes', 'v')
            ->leftJoin('p.depenses', 'd');

        $results = $qb->getQuery()->getResult();
        $bandes = [
            'all' => $results,
            'open' => [],
            'close' => []
        ];
        foreach ($results as $bande) {
            if ($bande['is_open']) {
                $bandes['open'][] = $bande[0];
            } else {
                $bandes['close'][] = $bande[0];
            }
        }

        return $bandes;
    }

    public function findLatestCloture()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.date_cloture IS NOT NULL')
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

    public function soldeCloture($bandesCloture): float
    {
        $bilan = array_reduce($bandesCloture, function ($current, $bande) {
            $current += $this->bilan($bande);
            return $current;
        }, 0);
        return Compte::COMPTE + $bilan;
    }

    public function bilan($entity): float
    {
        return $this->totalVente($entity) - $this->totalDepense($entity);
    }

    public function totalVente(Bande $entity): int
    {
        return array_reduce($entity->getVentes()->toArray(), function ($carry, $item) {
            /** @var Vente $item */
            return $carry + $item->getPrix();
        }, 0);
    }

    public function totalDepense(Bande $entity): int
    {
        return array_reduce($entity->getDepenses()->toArray(), function ($carry, $item) {
            /** @var Depense $item */
            return $carry + $item->getPrix();
        }, 0);
    }

    public function soldeCurrent($allBandes): float
    {
        $bilan = array_reduce($allBandes, function ($current, $bande) {
            $current += $this->bilan($bande[0]);
            return $current;
        }, 0);
        return Compte::COMPTE + $bilan;
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
