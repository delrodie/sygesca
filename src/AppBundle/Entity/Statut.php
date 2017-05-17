<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 *
 * @ORM\Table(name="statut")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatutRepository")
 */
class Statut
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=10, unique=true)
     */
    private $libelle;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Scout", mappedBy="statut")
    */
    private $scouts;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Statut
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    public function __toString() {
        return $this->getLibelle();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scouts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add scout
     *
     * @param \AppBundle\Entity\Scout $scout
     *
     * @return Statut
     */
    public function addScout(\AppBundle\Entity\Scout $scout)
    {
        $this->scouts[] = $scout;

        return $this;
    }

    /**
     * Remove scout
     *
     * @param \AppBundle\Entity\Scout $scout
     */
    public function removeScout(\AppBundle\Entity\Scout $scout)
    {
        $this->scouts->removeElement($scout);
    }

    /**
     * Get scouts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScouts()
    {
        return $this->scouts;
    }
}
