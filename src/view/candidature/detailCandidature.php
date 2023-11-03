<?php

/** @var $candidatures \app\src\model\dataObject\Postuler */

use app\src\model\Auth;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\dataObject\Roles;

$etudiant = (new EtudiantRepository([]))->getByIdFull($candidatures->getIdutilisateur());
$nometudiant = (new UtilisateurRepository([]))->getUserById($candidatures->getIdutilisateur())->getNomutilisateur();
$offre = (new OffresRepository())->getById($candidatures->getIdoffre());
print_r($offre);

?>

<div class="mt-6 border-zinc-100 pt-12 pb-24">
    <?php
    echo '<h2 class="font-bold text-lg">Postuler de
'.$nometudiant. " " . $etudiant->getLoginLDAP() .'
</h2>';
    ?>
    <dl class="divide-y divide-zinc-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Statut</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                <div>
                    <?php
                    if ($candidatures->getStatut() == "en attente") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
                    } else if ($candidatures->getStatut() == "valider") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Acceptée
    </span>";
                    } else if ($candidatures->getStatut() == "refuser") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                    } else if ($candidatures->getStatut() == "brouillon") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Archivée
    </span>";
                    }else{
                        echo $candidatures->getStatut();
                    }
                    ?>
                </div>
            </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Nom de l'offre</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $offre->getSujet() ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">CV de l'étudiant</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                <?php
                $filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/cv.pdf";
                echo "<a href='".$filepath."' download target='_blank'>";?> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                </a>
            </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Lettre de motivation de l'étudiant</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                <?php
                $filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/ltm.pdf";
                echo "<a href='".$filepath."' download target='_blank'>"; ?> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                </a>
            </dd>
        </div>
    </dl>
</div>


<?php
if($candidatures->getStatut() == "en attente" && Auth::has_role(Roles::Enterprise,Roles::Manager,Roles::Staff)){
    echo "<div class='flex flex-col mb-3'>
    <form action='/candidatures' method='POST'>
    <input type='hidden' name='idcandidature' value='".$candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."'>
    ";
     ?>

    <div class="flex flex-row gap-4 w-full">
        <input type="submit" name="action" value="Accepter"
               class="w-full text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"/>
        <input type="submit" name="action" value="Refuser"
               class="w-full text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"/>
    </div>

    <?php

    echo "</form>
    </div>";
}

if (Auth::has_role(Roles::Student, Roles::Manager) && $candidatures->getStatut() == "valider"){
    echo "<div class='flex flex-col mb-3'>
    <form action='/candidatures/contacter' method='POST'>
    <input type='hidden' name='identreprise' value='".$offre->getIdutilisateur()."'>
    <input type='hidden' name='idoffre' value='".$offre->getIdoffre()."'>
    <input type='hidden' name='idetudiant' value='".$candidatures->getIdUtilisateur()."'>
    ";
    ?>
    <input type="submit" name="action" value="Contacter l'entreprise"
           class="w-full text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"/>
    <?php
    echo "</form>
    </div>";
}
?>

