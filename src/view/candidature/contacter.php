<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>
<div class="w-full max-w-md pt-12 pb-24 gap-2 flex flex-col mx-auto max-w-md">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
        Contacter l'entreprise
    </h3>
    <p class="mt-1 text-md leading-5 text-gray-700 mb-5">
        Remplissez le formulaire ci-dessous pour envoyer un message à l'entreprise.
    </p>
    <div class="w-full gap-4 flex flex-col">
        <?php
        $form->start();

        $form->print_all_fields();
        $form->getError();
        ?>
        <div class="w-full flex justify-end mt-2">
            <?php
            $form->submit("Envoyer");
            ?>
        </div>
        <?php

        $form->end();

        ?>
    </div>
</div>
