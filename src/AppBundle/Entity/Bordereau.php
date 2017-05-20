<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Bordereau
 *
 * @ORM\Table(name="bordereau")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BordereauRepository")
 */
class Bordereau
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
     * @ORM\Column(name="numero", type="string", length=15, nullable=true, unique=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="string", length=6, nullable=true)
     */
    private $montant;

    /**
     * @var bool
     *
     * @ORM\Column(name="valide", type="boolean", nullable=true)
     */
    private $valide;

    /**
     * @var array
     *
     * @ORM\Column(name="cotisants", type="array", nullable=true)
     */
    private $cotisants;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"numero"})
     * @ORM\Column(name="slug", type="string", length=15)
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cotisation", inversedBy="bordereaux")
     * @ORM\JoinColumn(name="cotisation_id", referencedColumnName="id")
     */
     private $cotisation;


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
     * Set numero
     *
     * @param string $numero
     *
     * @return Bordereau
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return Bordereau
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set valide
     *
     * @param boolean $valide
     *
     * @return Bordereau
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return bool
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set cotisants
     *
     * @param array $cotisants
     *
     * @return Bordereau
     */
    public function setCotisants($cotisants)
    {
        $this->cotisants = $cotisants;

        return $this;
    }

    /**
     * Get cotisants
     *
     * @return array
     */
    public function getCotisants()
    {
        return $this->cotisants;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Bordereau
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
     * @return Bordereau
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
     * @return Bordereau
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
     * @return Bordereau
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
     * @return Bordereau
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
     * Set cotisation
     *
     * @param \AppBundle\Entity\Cotisation $cotisation
     *
     * @return Bordereau
     */
    public function setCotisation(\AppBundle\Entity\Cotisation $cotisation = null)
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    /**
     * Get cotisation
     *
     * @return \AppBundle\Entity\Cotisation
     */
    public function getCotisation()
    {
        return $this->cotisation;
    }
}
