<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24 mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Simulateur Pstage (Tuteur)</h1>
    <?php $form->start(); ?>
    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        $form->print_all_fields();
        $form->submit("Continuer");
        ?>
    </div>
    <?php
    $form->getError();
    $form->end();
    ?>

</div>
