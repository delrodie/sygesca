<?php

namespace AppBundle\Repository;

/**
 * GroupeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste des groupes par la région
     *
     * @author Delrodie AMOIKON
     * @version v1.0 17/05/2017 16:59
     */
    public function getListeGroupeParRegion($regionID)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQuery('
              SELECT g, d, r
              FROM AppBundle:Groupe g
              JOIN g.district d
              JOIN d.region r
              WHERE r.id = :regionID
              ORDER BY g.paroisse ASC
        ')->setParameter('regionID', $regionID)
        ;
        return $qb->getResult();
    }

    /**
     * Fonction de recherche des groupes
     * concernés par la region
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 17/05/2017 22:13
     */
    public function getGroupeByRegion($user)
    {
       //die($user);
        $em = $this->getEntityManager();

        $qb = $this->createQueryBuilder('p')
                   ->innerjoin('p.district', 'd')
                   ->innerjoin('d.region', 'r')
                   ->innerjoin('r.gestionnaires', 'g')
                   ->innerjoin('g.user', 'u')
                   ->where('u.id = :user')
                   ->orderBy('p.paroisse', 'ASC')
                   ->setParameter('user', $user);
         return $qb;
    }
}