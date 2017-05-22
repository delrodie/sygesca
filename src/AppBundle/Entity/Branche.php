<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Branche
 *
 * @ORM\Table(name="branche")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BrancheRepository")
 */
class Branche
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
     * @ORM\Column(name="nom", type="string", length=15, unique=true)
     */
    private $nom;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Scout", mappedBy="branche")
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Branche
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function __toString() {
        return $this->getNom();
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
     * @return Branche
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
