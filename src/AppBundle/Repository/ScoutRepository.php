<?php

namespace AppBundle\Repository;

use Symfony\Component\CssSelector\Node\CombinedSelectorNode;

/**
 * ScoutRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScoutRepository extends \Doctrine\ORM\EntityRepository
{
    /**
      * Generation du code composant le matricule du scout
      * Avec la methode mt_rand(10000000,99999999)
      *
      * Author: Delrodie AMOIKON
      * Since: v1.0 | 27/02/2017
      */
    public function generationCode()
    {

        // Affectation a code la valeur aleatoire generée
        $matricule = mt_rand(1000, 9999);

        return $matricule;
    }

    /**
     * Géneration d'une lettre aleatoire composant le matricule du scout
     *
     * Author: Delrodie AMOIKON
     * Since Version v1.0 | 27/02/2017
     */
    public function generationLettre()
    {
        // Liste des lettres de l'alphabet
        $alphabet="ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // Affectation d'une lettre aleatoire
        $lettre_aleatoire=$alphabet[rand(0,25)];

        return $lettre_aleatoire;
    }

    /**
     * Listes des scouts par région
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 17/05/2017 22:56
     */
    public function findScoutByUser($regionID)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQuery('
            SELECT s, g
            FROM AppBundle:Scout s
            JOIN s.groupe g
            JOIN g.district d
            JOIN d.region r
            WHERE r.id = :id
        ')->setParameter('id', $regionID)
        ;
        return $qb->getResult();
    }

    /**
     * Verification de l'existence du scout dans le système
     * Renvoie de la region s'il existe
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 18/05/2017 09:12
     */
    public function uniciteScout($nom, $prenoms, $date, $lieu)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQuery('
                SELECT r, d, g, s
                FROM AppBundle:Scout s
                JOIN s.groupe g
                JOIN g.district d
                JOIN d.region r
                WHERE s.nom = :nom
                AND s.prenoms = :prenoms
                AND s.datenaiss = :datenaissance
                AND s.lieunaiss = :lieu
        ')->setParameters(array(
              'nom' => $nom,
              'prenoms' => $prenoms,
              'datenaissance' => $date,
              'lieu'  => $lieu
        ))
        ;
        if ($qb->getResult()) {
          return $qb->getSingleResult();
        } else {
          return NULL;
        }
    }

    /**
     * Liste des adherants par region
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 18/05/2017 23:14
     */
    public function getAdherantByRegion($region, $cotisation)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQuery('
              SELECT r, d, g, s
              FROM AppBundle:Scout s
              JOIN s.groupe g
              JOIN g.district d
              JOIN d.region r
              WHERE r.id = :region
              AND s.cotisation <> :cotisation
              ORDER BY s.nom ASC
        ')->setParameters(array(
            'region'  => $region,
            'cotisation'  => $cotisation
        ))
        ;//dump($cotisation);die();
        return $qb->getResult();
    }

    /**
     * Liste de tous les adherants
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 19/05/2017 00:04
     */
    public function getAdherant($cotisation)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQuery('
              SELECT d, g, s
              FROM AppBundle:Scout s
              JOIN s.groupe g
              JOIN g.district d
              WHERE s.cotisation <> :cotisation
              ORDER BY s.nom ASC
        ')->setParameters(array(
            //'region'  => $region,
            'cotisation'  => $cotisation
        ))
        ;//dump($cotisation);die();
        return $qb->getResult();
    }

    /**
     * Recherche du scout dans le tableau
     *
     * Author: Delrodie AMOIKON
     * Date: 24/04/2017
     * Since: v1.0
     */
    public function findArray($array)
    {
       $qb = $this->createQueryBuilder('s')
                //->Select('p')
                ->Where('s.id IN (:array)')
                ->setParameter('array', $array);
        return $qb->getQuery()->getResult();
    }

    /**
     * Fonction de recherche du ID du scout
     *
     * Author: Delrodie AMOIKON
     * Date: 01/03/2017
     * Since: v1.0
     */
    public function getScoutID($scout)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQuery('
            SELECT c.id
            FROM AppBundle:Scout c
            WHERE c.slug = :id
        ')->setParameter('id', $scout)
        ;
        try {

            $id = $qb->getSingleResult();

            foreach ($id as $key => $value) {
                if ($value < 10) {
                   $num = '000'.$value;
                } elseif ($value < 100){
                   $num = '00'.$value;
                } elseif ($value < 1000){
                   $num = '0'.$value;
                } else{
                   $num = $value;
                }

                return $num;
            }

        } catch (NoResultException $e) {
            return $e;
        }
    }

    /**
     * Nombre de scouts par statut cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 03:25
     */
    public function getNombreStatutCotisant($statut, $annee)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->where('t.libelle LIKE :stat')
                   ->andWhere('s.cotisation = :annee')
                   ->setParameters(array(
                      'stat'  =>  '%'.$statut.'%' ,
                      'annee' =>  $annee
                ))
                ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre de scouts par statut cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 03:25
     */
    public function getNombreStatutCotisantRegional($statut, $annee, $region)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('t.libelle LIKE :stat')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('r.id = :region')
                   ->setParameters(array(
                      'stat'  =>  '%'.$statut.'%' ,
                      'annee' =>  $annee,
                      'region'  => $region
                ))
                ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre de scouts par genre cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 03:55
     */
    public function getNombreGenreCotisant($genre, $annee)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->where('s.sexe LIKE :genre')
                   ->andWhere('s.cotisation = :annee')
                   ->setParameters(array(
                      'genre'  =>  '%'.$genre.'%' ,
                      'annee' =>  $annee
                ))
                ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre de scouts de la region par genre cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 03:55
     */
    public function getNombreGenreCotisantRegional($genre, $annee, $region)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('s.sexe LIKE :genre')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('r.id = :region')
                   ->setParameters(array(
                      'genre'  =>  '%'.$genre.'%' ,
                      'annee' =>  $annee,
                      'region'  => $region
                ))
                ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre de jeunes par branche cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 17:34
     */
    public function getNombreBrancheCotisant($statut, $branche, $annee)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->where('t.libelle LIKE :stat')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('s.branche LIKE :branche')
                   ->setParameters(array(
                      'stat'  =>  '%'.$statut.'%' ,
                      'annee' =>  $annee,
                      'branche' => '%'.$branche.'%'
                ))
                ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre de jeunes par branche cotisant dans la region de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 17:34
     */
    public function getNombreBrancheCotisantRegional($statut, $branche, $annee, $region)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('t.libelle LIKE :stat')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('s.branche LIKE :branche')
                   ->andWhere('r.id = :region')
                   ->setParameters(array(
                      'stat'  =>  '%'.$statut.'%' ,
                      'annee' =>  $annee,
                      'branche' => '%'.$branche.'%',
                      'region'  => $region
                ))
                ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisant par statut de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 18:25
     */
    public function getNbTotStatutCotisant($statut, $annee)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->where('s.cotisation = :annee')
                   ->andWhere('t.libelle = :statut')
                   ->setParameters(array(
                      'annee' =>  $annee,
                      'statut'  => $statut
                   ))
                ;
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * Nombre total de cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 19:25
     */
    public function getNbTotCotisant($annee)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->where('s.cotisation = :annee')
                   ->setParameter('annee', $annee)
                ;
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * Nombre total de cotisant de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 19:25
     */
    public function getNbTotCotisantRegional($annee, $region)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('s.cotisation = :annee')
                   ->andWhere('r.id = :region')
                   ->setParameters(array(
                        'annee' => $annee,
                        'region'  => $region,
                   ))
                ;
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * Nombre total de cotisant par statut de l'année encours
     *
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 19:25
     */
    public function getNbTotStatutCotisantRegional($statut, $annee, $region)
    {

        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('s.cotisation = :annee')
                   ->andWhere('r.id = :region')
                   ->andWhere('t.libelle LIKE :stat')
                   ->setParameters(array(
                        'annee' => $annee,
                        'region'  => $region,
                        'stat'  => '%'.$statut.'%'
                   ))
                ;
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * Nombre total de cotisants de la region
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 20:29
     */
    public function getNbTotalCotisantParregion($region, $annee)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('r.id = :region')
                   ->andWhere('s.cotisation = :annee')
                   ->setParameters(array(
                      'region'  => $region,
                      'annee' => $annee
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants de la region
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 22:05
     */
    public function getNbStatutCotisantParregion($region, $statut, $annee)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('r.id = :region')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('t.libelle LIKE :statut')
                   ->setParameters(array(
                      'region'  => $region,
                      'annee' => $annee,
                      'statut'  => '%'.$statut.'%'
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants par genre de la region
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 22:34
     */
    public function getNbGenreCotisantParregion($region, $genre, $annee)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('r.id = :region')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('s.sexe LIKE :sexe')
                   ->setParameters(array(
                      'region'  => $region,
                      'annee' => $annee,
                      'sexe'  => '%'.$genre.'%'
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants de la region
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 22:05
     */
    public function getNbStatutBrancheCotisantParregion($region, $statut, $annee, $branche)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->join('d.region', 'r')
                   ->where('r.id = :region')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('t.libelle LIKE :statut')
                   ->andWhere('s.branche LIKE :branche')
                   ->setParameters(array(
                      'region'  => $region,
                      'annee' => $annee,
                      'statut'  => '%'.$statut.'%',
                      'branche'  => '%'.$branche.'%'
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants du district par branche
     * @author: Delrodie AMOIKON
     * @version v1.0 28/05/2017 22:05
     */
    public function getNbStatutBrancheCotisantParDistrict($district, $statut, $annee, $branche)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->where('g.id = :district')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('t.libelle LIKE :statut')
                   ->andWhere('s.branche LIKE :branche')
                   ->setParameters(array(
                      'district'  => $district,
                      'annee' => $annee,
                      'statut'  => '%'.$statut.'%',
                      'branche'  => '%'.$branche.'%'
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre de scouts enregistrés dans la plateforme
     */
    public function getTotalScout()
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants du district
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 20:29
     */
    public function getNbTotalCotisantParDistrict($district, $annee)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->where('d.id = :district')
                   ->andWhere('s.cotisation = :annee')
                   ->setParameters(array(
                      'district'  => $district,
                      'annee' => $annee
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants du district par statut
     * @author: Delrodie AMOIKON
     * @version v1.0 207/05/2017 22:05
     */
    public function getNbStatutCotisantParDistrict($district, $statut, $annee)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.statut', 't')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->where('d.id = :district')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('t.libelle LIKE :statut')
                   ->setParameters(array(
                      'district'  => $district,
                      'annee' => $annee,
                      'statut'  => '%'.$statut.'%'
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Nombre total de cotisants par genre deu district
     * @author: Delrodie AMOIKON
     * @version v1.0 20/05/2017 22:34
     */
    public function getNbGenreCotisantParDistrict($district, $genre, $annee)
    {
        $qb = $this->createQueryBuilder('s')
                   ->select('count(s.id)')
                   ->join('s.groupe', 'g')
                   ->join('g.district', 'd')
                   ->where('d.id = :district')
                   ->andWhere('s.cotisation = :annee')
                   ->andWhere('s.sexe LIKE :sexe')
                   ->setParameters(array(
                      'district'  => $district,
                      'annee' => $annee,
                      'sexe'  => '%'.$genre.'%'
                   ))
                   ;
      return $qb->getQuery()->getSingleScalarResult();
    }

}
