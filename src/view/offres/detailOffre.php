<?php

/** @var $offre \app\src\model\dataObject\Offre */

?>

<div class="w-full">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900"><?= $offre->getSujet() ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                <?php
                try {
                    $date = new DateTime($offre->getDatecreation());
                    echo "Publiée le " . $date->format('d/m/Y') . " à " . $date->format('H:i:s');
                } catch (Exception $e) {
                    echo "Something went wrong.";
                }
                ?></p>
        </div>
        <span class="inline-flex cursor-pointer  -space-x-px overflow-hidden rounded-md border bg-white shadow-sm">
  <a href="/offres/<?= $offre->getIdOffre() ?>/edit"
     class="cursor-pointer inline-block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:relative">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
         class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
</svg>
  </a>
            <?php if ($offre->getStatut() != "approved"): ?>
                <a href="/offres/<?= $offre->getIdOffre() ?>/validate"
                   class="inline-block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:relative">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
     class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
  </a><?php endif; ?>
            <?php if ($offre->getStatut() != "draft"): ?>
  <a href="/offres/<?= $offre->getIdOffre() ?>/archive" class="inline-block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:relative">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
         class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
</svg>
  </a>
            <?php endif; ?>

</span>
    </div>
    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Statut</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <div>
                        <?php
                        if ($offre->getStatut() == "pending") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
                        } else if ($offre->getStatut() == "approved") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                        } else if ($offre->getStatut() == "blocked") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                        } else if ($offre->getStatut() == "draft") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800\">
    Archivée
    </span>";
                        }
                        ?>
                    </div>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Thématique</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getThematique() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Entreprise</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><a
                            href="/entreprises/<?= $offre->getIdutilisateur() ?>"><?= $offre->getNomutilisateur() ?></a>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Durée</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getDuree() ?> an(s)
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Dates</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php
                    try {
                        $dateDebut = new DateTime($offre->getDatedebut());
                        $dateFin = new DateTime($offre->getDatefin());
                        if ($dateFin < new DateTime()) echo "Terminée";
                        else if (is_null($offre->getDatefin())) echo "Indéterminée";
                        else echo "Du " . $dateDebut->format('d/m/Y') . " au " . $dateFin->format('d/m/Y');
                    } catch (Exception $e) {
                        echo "Something went wrong.";
                    }
                    ?>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Année visée</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getAnneeVisee() ?>A
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Gratification</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getGratification() ?>
                    €/<?= $offre->getUnitegratification() ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Description</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?= $offre->getDescription() ?></dd>
            </div>
        </dl>
    </div>
    <a href="<?php echo $offre->getIdOffre(); ?>/postuler"
       class="mt-6 w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-zinc-600 hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500 sm:w-auto">
        Postuler
    </a>
</div>
