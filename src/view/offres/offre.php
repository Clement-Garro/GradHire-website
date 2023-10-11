<?php
/** @var $offre \app\src\model\dataObject\Offre
 * @var $utilisateurs array
 * @var $currentFilterURL string
 */

use app\src\model\Application;


?>
<a href="/offres/<?php echo $offre->getIdoffre(); ?>"
   class="offreBox rounded-[10px] relative cursor-pointer group bg-white p-4 shadow-sm hover:shadow min-w-[200px] shrink duration-150 border-2 border-zinc-200 hover:border-zinc-300">
    <div class="absolute top-0 right-0 flex flex-row gap-1 mt-11 mr-4">
        <form class="formAdminSupprimer flex items-center justify-center w-7 h-7 border-[1px] border-zinc-100 duration-150 bg-zinc-50 hover:invert rounded-full"
              method="POST" action="/offres/<?php echo $offre->getIdoffre(); ?>/edit">
            <input type="hidden" name="edit" value="<?php echo $offre->getIdoffre(); ?>">
            <button type="submit" value="Edit" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>

            </button>
        </form>
        <form class="formAdminSupprimer flex items-center justify-center w-7 h-7 border-[1px] border-zinc-100 duration-150 bg-zinc-50 hover:invert  rounded-full"
              method="POST" action="/offres/<?php echo $offre->getIdoffre(); ?>/delete">
            <input type="hidden" name="link" value="<?= $currentFilterURL; ?>">
            <input type="hidden" name="delete" value="<?php echo $offre->getIdoffre(); ?>">
            <button type="submit" value="Delete" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
            </button>
        </form>
    </div>
    <div class="w-full flex flex-row justify-between items-center">
    <span class="whitespace-nowrap rounded-md bg-zinc-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-zinc-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-3 h-3 mr-0.5">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5"/>
</svg>

        <?= ucfirst($offre->getThematique()) ?></span>
        <?php $dateCreation = new DateTime($offre->getDatecreation());
        $dateCreation = $dateCreation->format('d/m/Y');
        echo "<p class=\"block text-xs text-zinc-500\">" . $dateCreation . "</p>";
        ?>
    </div>
    <div class=" text-lg font-bold mt-2 text-zinc-900">
        <p><?= ucfirst($offre->getSujet()) ?></p>
    </div>
    <div>
        <p class="text-sm text-zinc-500">
            <?php
            //if description is too long (more than 50 characters), we cut it and add "..."
            if (strlen($offre->getDescription()) > 50) echo substr(ucfirst($offre->getDescription()), 0, 50) . "…";
            else echo ucfirst($offre->getDescription()) ?>
        </p>
    </div>
    <div class="mt-4 flex w-full gap-1">
        <div class="flex flex-row gap-1 items-center">
            <img class="w-5 h-5 rounded-full" src="<?= Application::getUser()->get_picture() ?>"
                 alt="Jese Leos avatar"/>
            <span class="whitespace-nowrap px-1 py-0.5 font-medium text-center flex justify-center items-center text-xs text-zinc-400">
            <?php
            $userId = $offre->getIdutilisateur();
            if (isset($utilisateurs[$userId])) echo $utilisateurs[$userId];
            else echo 'Inconnu';
            ?>
        </span>
        </div>
        <span class="group w-full pr-2 group-hover:pr-0 duration-150 inline-flex items-end justify-end gap-1 text-sm font-medium text-zinc-600"><span
                    aria-hidden="true"
                    class="block transition-all group-hover:ms-0.5 rtl:rotate-180">&rarr;</span></span>
    </div>
</a>

