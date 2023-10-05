<?php

namespace app\src\model\DataObject;

use app\src\model\DataObject\AbstractDataObject;

class Offres extends AbstractDataObject
{

  protected function getValueColonne(string $nomColonne): string
  {
    switch ($nomColonne) {
      case "idOffre":
        return $this->getIdOffre();
      case "durée":
        return $this->getDurée();
      case "thematique":
        return $this->getThematique();
      case "sujet":
        return $this->getSujet();
      case "nbJourTravailHebdo":
        return $this->getNbJourTravailHebdo();
      case "nbHeureTravailHebdo":
        return $this->getNbHeureTravailHebdo();
      case "Gratification":
        return $this->getGratification();
      case "unitegratification":
        return $this->getUnitegratification();
      case "avantageNature":
        return $this->getAvantageNature();
      case "dateDebut":
        return $this->getDateDebut();
      case "dateFin":
        return $this->getDateFin();
      case "pourvue":
        return $this->getPourvue();
      case "Statut":
        return $this->getStatut();
      case "anneeVisee":
        return $this->getAnneeVisee();
        case "idAnnee":
        return $this->getIdAnnee();
        case "idUtilisateur":
        return $this->getIdUtilisateur();
      default:
        return "";
    }
  }
    private int $idOffre;
    private ?string $duree;
    private string $thematique;
    private string $sujet;
    private ?int $nbJourTravailHebdo;
    private ?float $nbHeureTravailHebdo;
    private ?float $gratification;
    private ?string $unitegratification;
    private ?string $avantageNature;
    private string $dateDebut;
    private ?string $dateFin;
    private ?string $statut;
    private ?string $anneeVisee;
    private string $idAnnee;
    private int $idUtilisateur;
    private ?string $description;

    public function __construct
    (
        $idOffre,
        $duree,
        $thematique,
        $sujet,
        $nbJourTravailHebdo,
        $nbHeureTravailHebdo,
        $gratification,
        $unitegratification,
        $avantageNature,
        $dateDebut,
        $dateFin,
        $statut,
        $anneeVisee,
        $idAnnee,
        $idUtilisateur,
        $description
    )
    {
        $this->idOffre = $idOffre;
        $this->duree = $duree;
        $this->thematique = $thematique;
        $this->sujet = $sujet;
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
        $this->gratification = $gratification;
        $this->unitegratification = $unitegratification;
        $this->avantageNature = $avantageNature;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->statut = $statut;
        $this->anneeVisee = $anneeVisee;
        $this->idAnnee = $idAnnee;
        $this->idUtilisateur = $idUtilisateur;
        $this->description = $description;
    }

    /**
     * @param string $duree
     */
    public function setDuree(string $duree): void
    {
        $this->duree = $duree;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        switch ($nomColonne) {
            case "idOffre":
                return $this->getIdOffre();
            case "duree":
                return $this->getDuree();
            case "thematique":
                return $this->getThematique();
            case "sujet":
                return $this->getSujet();
            case "nbJourTravailHebdo":
                return $this->getNbJourTravailHebdo();
            case "nbHeureTravailHebdo":
                return $this->getNbHeureTravailHebdo();
            case "Gratification":
                return $this->getGratification();
            case "unitegratification":
                return $this->getUnitegratification();
            case "avantageNature":
                return $this->getAvantageNature();
            case "dateDebut":
                return $this->getDateDebut();
            case "dateFin":
                return $this->getDateFin();
            case "Statut":
                return $this->getStatut();
            case "anneeVisee":
                return $this->getAnneeVisee();
            case "idAnnee":
                return $this->getIdAnnee();
            case "idUtilisateur":
                return $this->getIdUtilisateur();
                case "description":
                return $this->getDescription();
            default:
                return "";
        }
    }

    /**
     * @return int
     */
    public function getIdOffre(): int
    {
        return $this->idOffre;
    }

    /**
     * @return string
     */
    public function getDuree(): string
    {
        return $this->duree;
    }

    /**
     * @return string
     */
    public function getThematique(): string
    {
        return $this->thematique;
    }

    /**
     * @param string $thematique
     */
    public function setThematique(string $thematique): void
    {
        $this->thematique = $thematique;
    }

    /**
     * @return string
     */
    public function getSujet(): string
    {
        return $this->sujet;
    }

    /**
     * @param string $sujet
     */
    public function setSujet(string $sujet): void
    {
        $this->sujet = $sujet;
    }

    /**
     * @return int
     */
    public function getNbJourTravailHebdo(): int
    {
        return $this->nbJourTravailHebdo;
    }

    /**
     * @param int $nbJourTravailHebdo
     */
    public function setNbJourTravailHebdo(int $nbJourTravailHebdo): void
    {
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
    }

    /**
     * @return float
     */
    public function getNbHeureTravailHebdo(): float
    {
        return $this->nbHeureTravailHebdo;
    }

    /**
     * @param float $nbHeureTravailHebdo
     */
    public function setNbHeureTravailHebdo(float $nbHeureTravailHebdo): void
    {
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
    }

    /**
     * @return float
     */
    public function getGratification(): float
    {
        return $this->gratification;
    }

    /**
     * @param float $gratification
     */
    public function setGratification(float $gratification): void
    {
        $this->gratification = $gratification;
    }

    /**
     * @return string
     */
    public function getUnitegratification(): string
    {
        return $this->unitegratification;
    }

    /**
     * @param string $unitegratification
     */
    public function setUnitegratification(string $unitegratification): void
    {
        $this->unitegratification = $unitegratification;
    }

    /**
     * @return string
     */
    public function getAvantageNature(): string
    {
        return $this->avantageNature;
    }

    /**
     * @param string $avantageNature
     */
    public function setAvantageNature(string $avantageNature): void
    {
        $this->avantageNature = $avantageNature;
    }

    /**
     * @return string
     */
    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    /**
     * @param string $dateDebut
     */
    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return string
     */
    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    /**
     * @param string $dateFin
     */
    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @return string
     */
    public function getAnneeVisee(): string
    {
        return $this->anneeVisee;
    }

    /**
     * @param string $anneeVisee
     */
    public function setAnneeVisee(string $anneeVisee): void
    {
        $this->anneeVisee = $anneeVisee;
    }

    /**
     * @return string
     */
    public function getIdAnnee(): string
    {
        return $this->idAnnee;
    }

    /**
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
