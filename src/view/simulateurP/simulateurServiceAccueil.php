<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (Service Accueil)</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        $form->print_all_fields();
        $form->submit("Continuer");
        ?>
    </div>
    <p> Le Service n'existe pas encore </p>
    <a href="/creerService"
       class="ml-3 inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Créer-le</a>
    <?php

    $form->getError();
    $form->end();
    ?>

</div>
