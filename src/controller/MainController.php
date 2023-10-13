<?php

namespace app\src\controller;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\Form\FormFile;
use app\src\model\Form\FormModel;
use app\src\model\Form\FormString;
use app\src\model\OffreForm;
use app\src\model\repository\CandidatureRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\Request;
use app\src\model\Users\Profile\EnterpriseProfile;
use app\src\model\Users\Roles;

class MainController extends Controller
{
    public function __construct()
    {
        //$this->registerMiddleware(new AuthMiddleware());
    }

    public function user_test(Request $req)
    {
        if (session_status() !== PHP_SESSION_NONE)
            session_destroy();
        $user = Auth::load_user_by_id($req->getRouteParams()["id"]);
        Auth::generate_token($user, "true");
        Application::$app->response->redirect('/');
    }

    public function contact(): string
    {
        return $this->render('contact');
    }

    public function profile(Request $req): string
    {
        $id = $req->getRouteParams()["id"] ?? null;
        if (!is_null($id)) {
            $user = Auth::load_user_by_id($id);
            if (is_null($user)) throw new NotFoundException();
        } else {
            $user = Application::getUser();
            if (is_null($user)) throw new ForbiddenException();
        }
        if ($user->role() === Roles::Enterprise) throw new NotFoundException();
        return $this->render('profile/profile', [
            'user' => $user
        ]);
    }

    public function archiver(Request $req): string
    {
        $user = (new UtilisateurRepository())->getUserById($req->getRouteParams()["id"]);
        (new UtilisateurRepository())->setUserToArchived($user);
        Application::$app->response->redirect('/utilisateurs/' . $req->getRouteParams()["id"]);
        return '';
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function edit_profile(Request $request): string
    {
        if (Application::isGuest()) throw new ForbiddenException();
        $id = $request->getRouteParams()["id"] ?? null;
        if (!is_null($id) && !Auth::has_role(Roles::Manager, Roles::Staff))
            throw new ForbiddenException();
        $user = is_null($id) ? Application::getUser() : Auth::load_user_by_id($id);
        if (is_null($user)) throw new NotFoundException();
        $attr = [];
        switch ($user->role()) {
            case Roles::Enterprise:
                $attr = array_merge($attr, [
                    "name" => FormModel::string("Nom entreprise")->required()->default($user->attributes()["nomutilisateur"]),
                    "email" => FormModel::email("Adresse mail")->required()->default($user->attributes()["emailutilisateur"]),
                    "phone" => FormModel::phone("Téléphone")->default($user->attributes()["numtelutilisateur"]),
                ]);
                break;
            case Roles::Tutor:
                $attr = array_merge($attr, [
                    "name" => FormModel::string("Prénom")->required()->default($user->attributes()["nomutilisateur"]),
                    "surname" => FormModel::string("Nom")->required()->default($user->attributes()["prenomtuteurp"]),
                    "email" => FormModel::string("Adresse mail")->required()->default($user->attributes()["emailutilisateur"]),
                    "fonction" => FormModel::select("Fonction", [
                        "tuteur" => "Tuteur",
                        "responsable" => "Responsable"
                    ])->required()->default($user->attributes()["fonctiontuteurp"]),
                ]);
                break;
            case  Roles::Student:
                $attr = array_merge($attr, [
                    "email" => FormModel::email("Adresse mail perso")->default($user->attributes()["mailperso"]),
                    "tel" => FormModel::phone("Téléphone")->numeric()->default($user->attributes()["numtelutilisateur"]),
                    "date" => FormModel::date("Date de naissance")->default($user->attributes()["datenaissance"])->before(new \DateTime()),
                    "studentnum" => FormModel::string("Numéro Etudiant")->default($user->attributes()["numetudiant"]),
                ]);
                break;
            case Roles::Teacher:
            case Roles::Manager:
            case Roles::Staff:
                $attr = array_merge($attr, [
                    "role" => FormModel::select("Role", [
                        "responsable" => "Responsable",
                        "enseignant" => "Enseignant",
                        "secretariat" => "Secretariat"
                    ])->required()->default($user->attributes()["role"]),
                ]);
                break;
        }
        $attr = array_merge(
            ["picture" => FormModel::file("Photo de profile")->id("image")->image()],
            $attr,
            ["bio" => FormModel::string("Biographie")->default($user->attributes()["bio"])->max(200)]
        );
        $form = new FormModel($attr);
        $form->useFile();

        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $picture = $form->getFile("picture");
                if (!is_null($picture)) $picture->save("pictures", $user->id());
                $user->update($form->getParsedBody());
                Application::$app->response->redirect('/profile');
                return '';
            }

        }
        return $this->render('profile/edit_profile', [
            'form' => $form
        ]);
    }

    public function dashboard(): string
    {
        return $this->render('dashboard/dashboard');
    }

    public function utilisateurs(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $utilisateur = (new UtilisateurRepository())->getUserById($id);
        if ($utilisateur == null && $id == null) {
            $utilisateurs = (new UtilisateurRepository())->getAll();
            return $this->render('utilisateurs/utilisateurs', ['utilisateurs' => $utilisateurs]);
        }
        return $this->render('utilisateurs/detail_utilisateur', ['utilisateur' => $utilisateur]);
    }

    public function entreprises(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $entreprise = (new EntrepriseRepository())->getByIdFull($id);
        if ($entreprise == null && $id != null) throw new NotFoundException();
        else if ($entreprise != null && $id != null) {
            $offres = (new OffresRepository())->getOffresByIdEntreprise($id);
            return $this->render('entreprise/detailEntreprise', ['entreprise' => $entreprise, 'offres' => $offres]);
        }

        $entreprises = (new EntrepriseRepository())->getAll();
        return $this->render('entreprise/entreprise', ['entreprises' => $entreprises]);
    }

    public function creeroffre(Request $request): string
    {
        if ($request->getMethod() === 'get') {
            return $this->render('/offres/create');
        } else {
            $type = $_POST['radios'];
            $titre = $_POST['titre'];
            $theme = $_POST['theme'];
            $nbjour = $_POST['nbjour'];
            $nbheure = $_POST['nbheure'];
            if ($type == "alternance") $distanciel = $_POST['distanciel'];
            else $distanciel = null;
            $salaire = $_POST['salaire'];
            $unitesalaire = "heures";
            $statut = "en attente";
            $avantage = $_POST['avantage'];
            $dated = $_POST['dated'];
            $datef = $_POST['datef'];
            $duree = $_POST['duree'];
            $description = $_POST['description'];
            $idUtilisateur = 51122324;
            $idOffre = null;
            if ($duree == 1) {
                $anneeVisee = "2";
            } else {
                $anneeVisee = "3";
            }
            $idAnnee = date("Y");
            //get current timestamp
            $datecreation = date("Y-m-d H:i:s");
            $offre = new Offre($idOffre, $duree, $theme, $titre, $nbjour, $nbheure, $salaire, $unitesalaire, $avantage, $dated, $datef, $statut, $anneeVisee, $idAnnee, $idUtilisateur, $description, $datecreation);
            print_r($offre);
            OffreForm::creerOffre($offre, $distanciel);
            return $this->render('/offres/create');
        }
    }

    public function deleteOffre(Request $request): void
    {
        if ($request->getMethod() === 'post') {
            $id = $request->getRouteParams()['id'] ?? null;
            $offre = (new OffresRepository())->getById($id);
            $url = $_POST['link'];
            if ($offre == null && $id != null) throw new NotFoundException();
            else if ($offre != null && $id != null) {
                (new OffresRepository())->updateToDraft($id);
                Application::$app->response->redirect($url);
            }
        }
    }

    public function offres(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getByIdWithUser($id);

        if ($offre == null && $id != null) throw new NotFoundException();
        else if ($offre != null && $id != null) return $this->render('offres/detailOffre', ['offre' => $offre]);

        $filter = self::constructFilter();

        if (empty($search) && empty($filter)) $offres = (new OffresRepository())->getAll();
        else $offres = (new OffresRepository())->search($filter);

        $userIdList = [];
        foreach ($offres as $offre) $userIdList[] = $offre->getIdutilisateur();
        $utilisateurRepository = new UtilisateurRepository();
        $utilisateurs = array();

        if (!empty($userIdList)) {
            foreach ($userIdList as $userId) {
                if (!isset($utilisateurs[$userId])) {
                    $utilisateur = $utilisateurRepository->getUserById($userId);
                    $utilisateurs[$userId] = $utilisateur->getNomutilisateur();
                }
            }
        }

        $currentFilterURL = "/offres?" . http_build_query($filter);
        return $this->render('offres/listOffres', ['offres' => $offres, 'utilisateurs' => $utilisateurs, 'currentFilterURL' => $currentFilterURL]);
    }

    private static function constructFilter(): array
    {
        $filter = array();
//        if (Auth::has_role(["student"])) {
//            if (isset($_GET['statut'])) $filter['statut'] = $_GET['statut'];
//        } else {
//            $filter['statut'] = "staff";
//        }
        if (isset($_GET['sujet'])) $filter['sujet'] = $_GET['sujet'];
        else $filter['sujet'] = "";
        if (isset($_GET['thematique'])) {
            $filter['thematique'] = "";
            foreach ($_GET['thematique'] as $key => $value) {
                if ($filter['thematique'] == null) $filter['thematique'] = $value;
                else if ($filter['thematique'] != null) $filter['thematique'] .= ',' . $value;
            }
        }
        if (isset($_GET['anneeVisee'])) $filter['anneeVisee'] = $_GET['anneeVisee'];
        if (isset($_GET['duree'])) $filter['duree'] = $_GET['duree'];
        if (isset($_GET['alternance'])) $filter['alternance'] = $_GET['alternance'];
        if (isset($_GET['stage'])) $filter['stage'] = $_GET['stage'];
        if (isset($_GET['gratificationMin'])) {
            if ($_GET['gratificationMin'] == "") $filter['gratificationMin'] = null;
            else if ($_GET['gratificationMin'] < 4.05) $filter['gratificationMin'] = 4.05;
            else if ($_GET['gratificationMin'] > 15) $filter['gratificationMin'] = 15;
            else $filter['gratificationMin'] = $_GET['gratificationMin'];
        }
        if (isset($_GET['gratificationMax'])) {
            if ($_GET['gratificationMax'] == "") $filter['gratificationMax'] = null;
            else if ($_GET['gratificationMax'] < 4.05) $filter['gratificationMax'] = 4.05;
            else if ($_GET['gratificationMax'] > 15) $filter['gratificationMax'] = 15;
            else $filter['gratificationMax'] = $_GET['gratificationMax'];
        }
        return $filter;
    }

    public function candidatures(Request $request): string
    {


        $id = $request->getRouteParams()['id'] ?? null;
        $candidatures = (new CandidatureRepository())->getById($id);
        if ($candidatures != null && $id != null) {
            return $this->render('candidature/detailCandidature', ['candidatures' => $candidatures]);
        }

        $candidaturesrepose = new CandidatureRepository();
        $candidatures = ($candidaturesrepose->getAll());

        if ($request->getMethod() === 'post') {
            $id = $request->getBody()['idcandidature'] ?? null;
            if ($request->getBody()['action'] === 'Accepter') {
                $sql = "UPDATE Candidature SET etatcandidature='Validé par secrétariat' WHERE idcandidature=$id";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute();
                $candidaturesrepose = new CandidatureRepository();
                $candidatures = ($candidaturesrepose->getAll());
                return $this->render('candidature/listCandidatures', ['candidatures' => $candidatures]);
            } else {
                $sql = "UPDATE Candidature SET etatcandidature='Refusé' WHERE idcandidature=$id";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute();
                $candidaturesrepose = new CandidatureRepository();
                $candidatures = ($candidaturesrepose->getAll());
                return $this->render('candidature/listCandidatures', ['candidatures' => $candidatures]);
            }
        }
        return $this->render('candidature/listCandidatures', ['candidatures' => $candidatures]);
    }

    /**
     * @throws NotFoundException
     * @throws ServerErrorException
     * @throws ForbiddenException
     */
    public function postuler(Request $request): string
    {
        if (!Auth::has_role(Roles::Student)) throw new ForbiddenException();
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getById($id);

        if (!$offre) throw  new NotFoundException();

        $form = new FormModel([
            "cv" => FormModel::file("CV")->required()->pdf(),
            "ltm" => FormModel::file("Lettre de motivation")->required()->pdf()
        ]);
        $form->useFile();

        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $path = "uploads/" . $id . "_" . Application::getUser()->id();
                if (!$form->getFile("cv")->save($path, "cv") ||
                    !$form->getFile("ltm")->save($path, "ltm")) {
                    $form->setError("Impossible de télécharger tous les fichiers");
                    return '';
                }
                $stmt = Database::get_conn()->prepare("INSERT INTO `Candidature`(`idoffre`, `idutilisateur`) VALUES (?,?)");
                $stmt->execute([$id, Application::getUser()->id()]);
                Application::$app->response->redirect('/offres');
            }

        }
        return $this->render('candidature/postuler', [
            'form' => $form
        ]);
    }
}
