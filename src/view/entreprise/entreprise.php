<?php
/** @var $entreprises \app\src\model\dataObject\Entreprise */
?>
<div class="overflow-x-auto w-full">
    <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Nom d'entreprise
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Email
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Téléphone
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-gray-900">
                Site web
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
        <?php foreach ($entreprises as $entreprise) { ?>
            <tr class="odd:bg-gray-50">
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                    <?= $entreprise->getNomutilisateur(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    $email = $entreprise->getEmailutilisateur();
                    if ($email != null) echo $email;
                    else echo("Non renseigné");
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    if ($entreprise->getNumtelutilisateur() == null) echo("Non renseigné");
                    else echo $entreprise->getNumtelutilisateur();
                    ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                    <?php
                    if ($entreprise->getSiteweb() == null) echo("Non renseigné");
                    else echo $entreprise->getSiteweb(); ?>
                </td>
                <td class="whitespace-nowrap px-4 py-2">
                    <a href="/entreprises/<?= $entreprise->getIdutilisateur(); ?>"
                       class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                        plus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
</div>