<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\TuteurEntreprise;
use app\src\model\Form\FormModel;

class TuteurEntrepriseRepository extends ProRepository
{
    protected static string $create_function = "creerTuteur";
    protected static string $view = "tuteurvue";
    private string $nomtable = "tuteurvue";

    /**
     * @throws ServerErrorException
     */
    public static function generateAccountToken(UtilisateurRepository $entreprise, string $email, FormModel $form): void
    {
        try {
            $sql = "SELECT * FROM CreationCompteTuteur WHERE email = ? AND idUtilisateur = ?";
            $statement = Database::get_conn()->prepare($sql);
            $statement->execute([$email, $entreprise->getId()]);
            $count = $statement->rowCount();
            if ($count > 0) {
                $form->setError("Cette adresse mail à déjà été ajoutée.");
                return;
            }
            $token = bin2hex(random_bytes(15));
            $sql = "INSERT INTO CreationCompteTuteur (idUtilisateur, email, tokenCreation) VALUES (?, ?,?)";
            $statement = Database::get_conn()->prepare($sql);
            $statement->execute([$entreprise->getId(), $email, $token]);
            MailRepository::send_mail([$email], "Création de compte tuteur", '
<div>
<p>Vous avez été ajouté en tant que tuteur de l\'entreprise ' . $entreprise->attributes["nom"] . '</p>
<a href="' . HOST . '/registerTutor/' . $token . '">Créer mon compte</a>
</div>');
            $form->setSuccess("Un email à été envoyé à l'adresse mail indiquée.");
        } catch (\Exception) {
            throw new ServerErrorException();
        }

    }

    /**
     * @throws ServerErrorException
     */
    public static function register(array $body, array $tokenData, FormModel $form): bool
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM Utilisateur WHERE email = ?");
            $statement->execute([$tokenData["email"]]);
            $count = $statement->rowCount();
            if ($count > 0) {
                $form->setError("Un compte existe déjà avec cette adresse mail.");
                self::deleteCreationToken($tokenData["tokencreation"]);
                return false;
            }
            $user = self::save([$body["name"], $body["surname"], $tokenData["email"], "tuteur", $tokenData["identreprise"], password_hash($body["password"], PASSWORD_DEFAULT), $body["phone"]]);
            Auth::generate_token($user, false);
            self::deleteCreationToken($tokenData["tokencreation"]);
            return true;
        } catch (\Exception $e) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    private static function deleteCreationToken(string $token): void
    {
        try {
            $statement = Database::get_conn()->prepare("DELETE FROM CreationCompteTuteur WHERE tokenCreation = ?");
            $statement->execute([$token]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public static function getIdEntrepriseByIdTutor(int $userid): ?int
    {
        $sql = "SELECT idEntreprise FROM tuteurvue WHERE idUtilisateur = ?";
        $statement = Database::get_conn()->prepare($sql);
        $statement->execute([$userid]);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $statement->fetch();
        if (!$result) return null;
        return $result["identreprise"];
    }

    /**
     * @throws ServerErrorException
     */
    public function getAllTuteursByIdEntreprise($idEntreprise, bool $getAll = false): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur WHERE idEntreprise = :idEntreprise" . ($getAll ? "" : " AND u.archiver = 0");
        try {
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idEntreprise' => $idEntreprise]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if (!$resultat) return null;
            foreach ($resultat as $key => $tuteur) {
                $resultat[$key] = $this->construireTuteurProDepuisTableau($tuteur);
            }
            return $resultat;
        } catch (\Exception) {
            throw new ServerErrorException('erreur getAllTuteursByIdEntreprise');
        }

    }

    public function construireTuteurProDepuisTableau(array $tuteurData): ?TuteurEntreprise
    {
        return new TuteurEntreprise(
            $tuteurData
        );
    }

    public function getAll(bool $getArchived = false): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur WHERE u.archiver = 0";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $requete->fetchAll();
        if (!$data) return null;
        $resultat = [];
        foreach ($data as $tuteur) {
            $resultat[] = $this->construireTuteurProDepuisTableau($tuteur);
        }
        return $resultat;
    }

    public function getNomtable(): string
    {
        return $this->nomtable;
    }

    public function getById($idTuteur): ?TuteurEntreprise
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur ON $this->nomtable.idUtilisateur = Utilisateur.idUtilisateur WHERE $this->nomtable.idUtilisateur = :idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $idTuteur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) {
            return null;
        }
        return $this->construireTuteurProDepuisTableau($resultat);
    }

    public function getFullByEntreprise(mixed $idEntreprise): ?array
    {
        $sql = "SELECT * FROM $this->nomtable WHERE $this->nomtable.idEntreprise = :idEntreprise";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idEntreprise' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) {
            return null;
        }
        foreach ($resultat as $key => $tuteur) {
            $resultat[$key] = $this->construireTuteurProDepuisTableau($tuteur);
        }
        return $resultat;
    }

    public function create(mixed $nom, mixed $prenom, mixed $fonction, mixed $tel, mixed $email, mixed $idEntreprise)
    {
        $sql = "SELECT creerTuteur(:prenom, :nom, :email, :fonction, :idEntreprise, :hash, :tel)  ";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'fonction' => $fonction,
            'idEntreprise' => $idEntreprise,
            'hash' => null,
            'tel' => $tel
        ]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        return $resultat;
    }

    protected function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "fonction",
            "prenom",
            "idEntreprise",
        ];
    }
}