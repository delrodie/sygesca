<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cotisation
 *
 * @ORM\Table(name="cotisation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CotisationRepository")
 */
class Cotisation
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
     * @ORM\Column(name="annee", type="string", length=10, unique=true)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="cadre", type="string", length=4, nullable=true)
     */
    private $cadre;

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=4, nullable=true)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="groupe", type="string", length=4, nullable=true)
     */
    private $groupe;

    /**
     * @var string
     *
     * @ORM\Column(name="jeune", type="string", length=4, nullable=true)
     */
    private $jeune;

    /**
     * @var string
     *
     * @ORM\Column(name="cn", type="string", length=5, nullable=true)
     */
    private $cn;

    /**
     * @var string
     *
     * @ORM\Column(name="cnd", type="string", length=5, nullable=true)
     */
    private $cnd;

    /**
     * @var string
     *
     * @ORM\Column(name="aine", type="string", length=5, nullable=true)
     */
    private $aine;

    /**
     * @var string
     *
     * @ORM\Column(name="equipenationale", type="string", length=5, nullable=true)
     */
    private $equipenationale;

    /**
     * @var string
     *
     * @ORM\Column(name="cac", type="string", length=5, nullable=true)
     */
    private $cac;

    /**
     * @var string
     *
     * @ORM\Column(name="cr", type="string", length=5, nullable=true)
     */
    private $cr;

    /**
     * @var string
     *
     * @ORM\Column(name="equiperegionale", type="string", length=5, nullable=true)
     */
    private $equiperegionale;

    /**
     * @var string
     *
     * @ORM\Column(name="cd", type="string", length=5, nullable=true)
     */
    private $cd;

    /**
     * @var string
     *
     * @ORM\Column(name="equipegroupe", type="string", length=5, nullable=true)
     */
    private $equipegroupe;

    /**
     * @var string
     *
     * @ORM\Column(name="equipedistrict", type="string", length=5, nullable=true)
     */
    private $equipedistrict;

    /**
     * @var string
     *
     * @ORM\Column(name="cu", type="string", length=5, nullable=true)
     */
    private $cu;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"annee"})
     * @ORM\Column(name="slug", type="string", length=10)
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
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bordereau", mappedBy="cotisation")
    */
    private $bordereaux;


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
     * Set annee
     *
     * @param string $annee
     *
     * @return Cotisation
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set cadre
     *
     * @param string $cadre
     *
     * @return Cotisation
     */
    public function setCadre($cadre)
    {
        $this->cadre = $cadre;

        return $this;
    }

    /**
     * Get cadre
     *
     * @return string
     */
    public function getCadre()
    {
        return $this->cadre;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return Cotisation
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set groupe
     *
     * @param string $groupe
     *
     * @return Cotisation
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return string
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set jeune
     *
     * @param string $jeune
     *
     * @return Cotisation
     */
    public function setJeune($jeune)
    {
        $this->jeune = $jeune;

        return $this;
    }

    /**
     * Get jeune
     *
     * @return string
     */
    public function getJeune()
    {
        return $this->jeune;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Cotisation
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
     * @return Cotisation
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
     * @return Cotisation
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
     * @return Cotisation
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
     * @return Cotisation
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
     * Constructor
     */
    public function __construct()
    {
        $this->bordereaux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bordereaux
     *
     * @param \AppBundle\Entity\Bordereau $bordereaux
     *
     * @return Cotisation
     */
    public function addBordereaux(\AppBundle\Entity\Bordereau $bordereaux)
    {
        $this->bordereaux[] = $bordereaux;

        return $this;
    }

    /**
     * Remove bordereaux
     *
     * @param \AppBundle\Entity\Bordereau $bordereaux
     */
    public function removeBordereaux(\AppBundle\Entity\Bordereau $bordereaux)
    {
        $this->bordereaux->removeElement($bordereaux);
    }

    /**
     * Get bordereaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBordereaux()
    {
        return $this->bordereaux;
    }

    public function __toString() {
        return $this->getAnnee();
    }

    /**
     * Set cn
     *
     * @param string $cn
     *
     * @return Cotisation
     */
    public function setCn($cn)
    {
        $this->cn = $cn;

        return $this;
    }

    /**
     * Get cn
     *
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * Set cnd
     *
     * @param string $cnd
     *
     * @return Cotisation
     */
    public function setCnd($cnd)
    {
        $this->cnd = $cnd;

        return $this;
    }

    /**
     * Get cnd
     *
     * @return string
     */
    public function getCnd()
    {
        return $this->cnd;
    }

    /**
     * Set aine
     *
     * @param string $aine
     *
     * @return Cotisation
     */
    public function setAine($aine)
    {
        $this->aine = $aine;

        return $this;
    }

    /**
     * Get aine
     *
     * @return string
     */
    public function getAine()
    {
        return $this->aine;
    }

    /**
     * Set equipenationale
     *
     * @param string $equipenationale
     *
     * @return Cotisation
     */
    public function setEquipenationale($equipenationale)
    {
        $this->equipenationale = $equipenationale;

        return $this;
    }

    /**
     * Get equipenationale
     *
     * @return string
     */
    public function getEquipenationale()
    {
        return $this->equipenationale;
    }

    /**
     * Set cac
     *
     * @param string $cac
     *
     * @return Cotisation
     */
    public function setCac($cac)
    {
        $this->cac = $cac;

        return $this;
    }

    /**
     * Get cac
     *
     * @return string
     */
    public function getCac()
    {
        return $this->cac;
    }

    /**
     * Set cr
     *
     * @param string $cr
     *
     * @return Cotisation
     */
    public function setCr($cr)
    {
        $this->cr = $cr;

        return $this;
    }

    /**
     * Get cr
     *
     * @return string
     */
    public function getCr()
    {
        return $this->cr;
    }

    /**
     * Set equiperegionale
     *
     * @param string $equiperegionale
     *
     * @return Cotisation
     */
    public function setEquiperegionale($equiperegionale)
    {
        $this->equiperegionale = $equiperegionale;

        return $this;
    }

    /**
     * Get equiperegionale
     *
     * @return string
     */
    public function getEquiperegionale()
    {
        return $this->equiperegionale;
    }

    /**
     * Set cd
     *
     * @param string $cd
     *
     * @return Cotisation
     */
    public function setCd($cd)
    {
        $this->cd = $cd;

        return $this;
    }

    /**
     * Get cd
     *
     * @return string
     */
    public function getCd()
    {
        return $this->cd;
    }

    /**
     * Set equipegroupe
     *
     * @param string $equipegroupe
     *
     * @return Cotisation
     */
    public function setEquipegroupe($equipegroupe)
    {
        $this->equipegroupe = $equipegroupe;

        return $this;
    }

    /**
     * Get equipegroupe
     *
     * @return string
     */
    public function getEquipegroupe()
    {
        return $this->equipegroupe;
    }

    /**
     * Set equipedistrict
     *
     * @param string $equipedistrict
     *
     * @return Cotisation
     */
    public function setEquipedistrict($equipedistrict)
    {
        $this->equipedistrict = $equipedistrict;

        return $this;
    }

    /**
     * Get equipedistrict
     *
     * @return string
     */
    public function getEquipedistrict()
    {
        return $this->equipedistrict;
    }

    /**
     * Set cu
     *
     * @param string $cu
     *
     * @return Cotisation
     */
    public function setCu($cu)
    {
        $this->cu = $cu;

        return $this;
    }

    /**
     * Get cu
     *
     * @return string
     */
    public function getCu()
    {
        return $this->cu;
    }
}
