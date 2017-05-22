<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupeRepository")
 */
class Groupe
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
     * @ORM\Column(name="paroisse", type="string", length=125)
     */
    private $paroisse;

    /**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=125, nullable=true)
     */
    private $localite;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"paroisse","localite"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="publie_par", type="string", length=25, nullable=true)
     */
    private $publiePar;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="modifie_par", type="string", length=25, nullable=true)
     */
    private $modifiePar;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="publie_le", type="datetimetz", nullable=true)
     */
    private $publieLe;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modifie_le", type="datetimetz", nullable=true)
     */
    private $modifieLe;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\District", inversedBy="groupes")
   * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
   */
   private $district;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Scout", mappedBy="groupe")
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
     * Set paroisse
     *
     * @param string $paroisse
     *
     * @return Groupe
     */
    public function setParoisse($paroisse)
    {
        $this->paroisse = $paroisse;

        return $this;
    }

    /**
     * Get paroisse
     *
     * @return string
     */
    public function getParoisse()
    {
        return $this->paroisse;
    }

    /**
     * Set localite
     *
     * @param string $localite
     *
     * @return Groupe
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Groupe
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set publiePar
     *
     * @param string $publiePar
     *
     * @return Groupe
     */
    public function setPubliePar($publiePar)
    {
        $this->publiePar = $publiePar;

        return $this;
    }

    /**
     * Get publiePar
     *
     * @return string
     */
    public function getPubliePar()
    {
        return $this->publiePar;
    }

    /**
     * Set modifiePar
     *
     * @param string $modifiePar
     *
     * @return Groupe
     */
    public function setModifiePar($modifiePar)
    {
        $this->modifiePar = $modifiePar;

        return $this;
    }

    /**
     * Get modifiePar
     *
     * @return string
     */
    public function getModifiePar()
    {
        return $this->modifiePar;
    }

    /**
     * Set publieLe
     *
     * @param \DateTime $publieLe
     *
     * @return Groupe
     */
    public function setPublieLe($publieLe)
    {
        $this->publieLe = $publieLe;

        return $this;
    }

    /**
     * Get publieLe
     *
     * @return \DateTime
     */
    public function getPublieLe()
    {
        return $this->publieLe;
    }

    /**
     * Set modifieLe
     *
     * @param \DateTime $modifieLe
     *
     * @return Groupe
     */
    public function setModifieLe($modifieLe)
    {
        $this->modifieLe = $modifieLe;

        return $this;
    }

    /**
     * Get modifieLe
     *
     * @return \DateTime
     */
    public function getModifieLe()
    {
        return $this->modifieLe;
    }

    /**
     * Set district
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return Groupe
     */
    public function setDistrict(\AppBundle\Entity\District $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \AppBundle\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    public function __toString() {
        return $this->getParoisse();
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
     * @return Groupe
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
