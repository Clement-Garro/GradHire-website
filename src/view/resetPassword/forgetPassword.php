<?php

/**
 * @var FormModel $form
 */

use app\src\model\Form\FormModel;

?>

<div class="w-full gap-4 mx-auto flex flex-col mt-4 py-[50px]">
    <h2 class="text-3xl">Modifier mon mot de passe</h2>

    <?php
    if (isset($form)) {
        $form->start();
        $form->print_all_fields();
        ?>
        <div class="w-full mt-4"><?php
        $form->submit("Modifier");
        ?></div><?php
        $form->getError();
        $form->end();
    } else {
        ?>
        <span class="text-zinc-600 mb-5">Un email vous a été envoyé pour changer votre mot de passe.</span>
        <?php
    }
    ?>
</div>