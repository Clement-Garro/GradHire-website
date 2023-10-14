<?php

/** @var $offres \app\src\model\dataObject\Offre
 */


?>

<div class="w-full pt-12 pb-24">
        <?php if ($offres != null) { ?>
            <div class="px-4 py-6 sm:gap-4 sm:px-0">
                <div class="w-full">
                    <table class="w-full divide-y-2 divide-gray-200 bg-white text-sm">
                        <thead class="ltr:text-left rtl:text-right">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Sujet
                            </th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Thématique
                            </th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Date de création
                            </th>
                            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                                Statut
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200">
                        <?php
                        if ($offres != null)
                            foreach ($offres as $offre) { ?>
                                <tr class="odd:bg-zinc-50">
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                                        <?= $offre['sujet']; ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                                        <?php
                                        $thematique = $offre['thematique'];
                                        if ($thematique != null) echo $thematique;
                                        else echo("Non renseigné");
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                                        <?php
                                        $dateCreation = new DateTime($offre['datecreation']);
                                        $dateCreation = $dateCreation->format('d/m/Y H:i:s');
                                        echo $dateCreation;
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                                        <?php
                                        if ($offre['status'] == "pending") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
</span>";
                                        } else if ($offre['status'] == "approved") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                                        } else if ($offre['status'] == "blocked") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                                        } else if ($offre['status'] == "draft") {
                                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800\">
    Archivée
    </span>";
                                        }
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <a href="/offres/<?= $offre['idoffre']; ?>"
                                           class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                            plus</a>
                                    </td>
                                </tr>
                            <?php }
                        ?>

                        </tbody>

                    </table>
                </div>
            </div>
        <?php } ?>
</div>
