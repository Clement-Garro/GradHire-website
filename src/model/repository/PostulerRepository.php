<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Postuler;

class PostulerRepository extends AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    private string $nomTable = "PostulerVue";

    /**
     * @throws ServerErrorException
     */
    public function getById($idOffre, $idUtilisateur): ?Postuler
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idOffre=:idOffre AND idUtilisateur=:idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idOffre' => $idOffre, 'idUtilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) return null;
        return $this->construireDepuisTableau($resultat);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Postuler
    {
        return new Postuler(
            $dataObjectFormatTableau['sujet'],
            $dataObjectFormatTableau['nom'],
            $dataObjectFormatTableau['dates'],
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['identreprise'],
            $dataObjectFormatTableau['statut']
        );
    }

    public function getByIdEntreprise($identreprise, string $etat): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idUtilisateur= :id AND statut=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise, 'etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    public function getCandidaturesAttenteEntreprise($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE identreprise= :id AND statut LIKE 'en attente%'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    public function getCandidaturesAttenteEtudiant($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idUtilisateur= :id AND statut LIKE 'en attente%'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    public function getByIdEtudiant($idEtudiant, string $etat): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable  WHERE idUtilisateur= :id AND statut=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $idEtudiant, 'etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;

    }

    public function setStatutPostuler(int $idutilisateur, int $idoffre, string $etat): void
    {
        $sql = "UPDATE $this->nomTable SET statut=:etat WHERE idUtilisateur=:idutilisateur AND idOffre=:idoffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['etat' => $etat, 'idutilisateur' => $idutilisateur, 'idoffre' => $idoffre]);
    }

    public function getByStatement(string $etat): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    public function getByStatementAttente(): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut LIKE 'en attente%'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM $this->nomTable;";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if (!$resultat) return null;
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    public function getIfSuivi(int $idUtilisateur, $idetu, $idoffre): bool
    {
        $statement = Database::get_conn()->prepare("SELECT * FROM Supervise WHERE idutilisateur = :idutilisateur AND idetudiant = :idetudiant AND idoffre = :idoffre");
        $statement->bindParam(":idutilisateur", $idUtilisateur);
        $statement->bindParam(":idetudiant", $idetu);
        $statement->bindParam(":idoffre", $idoffre);
        $statement->execute();
        $data = $statement->fetch();
        if ($data == null) return false;
        return true;
    }

    public function getByIdOffre(mixed $idOffre): ?array
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idOffre=:idOffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idOffre' => $idOffre]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    /**
     * @throws ServerErrorException
     */
    public function getSiTuteurPostuler(?int $getIdUtilisateur, ?int $getIdOffre)
    {
        try {
        $sql = "SELECT * FROM Supervise WHERE idEtudiant=:idUtilisateur AND idOffre=:idOffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $getIdUtilisateur, 'idOffre' => $getIdOffre]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) return false;
        return true;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getSiTuteurPostuler');
        }
    }

    public function getTuteurByIdOffre(mixed $idOffre): ?array
    {
        try {
        $sql = "SELECT * FROM Supervise WHERE idOffre=:idOffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idOffre' => $idOffre]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) return null;
        return $resultat;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getTuteurByIdOffre');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function refuserCandidature(int $idutilisateur, mixed $idOffre): void
    {
        try{
            $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idutilisateur
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs refuserCandidature');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerCandidatureEtudiant(mixed $idEtudiant, mixed $idOffre): void
    {
        try{
            $sql = "UPDATE Postuler SET statut = 'en attente tuteur' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idEtudiant
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs validerCandidatureEtudiant');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerCandidatureEntreprise(int $idUtilisateur, int $idOffre): void
    {
        try{
            $sql = "UPDATE Postuler SET statut = 'en attente etudiant' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idUtilisateur,
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs validerCandidatureEntreprise');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getByStatementAttenteTuteur()
    {
        try {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut = 'en attente tuteur' OR statut = 'en attente responsable'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getByStatementAttenteTuteur');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getByStatementTuteur(int $idutilisateur, string $string)
    {
        try {
            $sql = "SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur,idEntreprise,p.statut FROM $this->nomTable p JOIN Supervise su ON su.idOffre=p.idOffre WHERE p.idUtilisateur=:idutilisateur AND su.statut = :statut";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idutilisateur' => $idutilisateur,
                'statut' => $string
            ]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = [];
            foreach ($requete as $item) {
                $resultat[] = $this->construireDepuisTableau($item);
            }
            return $resultat;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getByStatementTuteur');
        }
    }


    protected
    function getNomColonnes(): array
    {
        return [
            "sujet",
            "nom",
            "dates",
            "idOffre",
            "idUtilisateur",
            "statut"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "PostulerVue";
    }
}