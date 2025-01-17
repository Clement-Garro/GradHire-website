<?php

use app\src\model\Form\FormModel;

/** @var $form FormModel */
/** @var $nom String */

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">
    <h1 class="text-3xl font-bold text-center">Simulateur Pstage <?php echo $nom ?></h1>

    <?php
    $form->start();
    if ($nom != "Créer un service d'accueil") {
        $form->print_all_fields();
    } else {
        $form->print_fields(["nomService", "memeAdresse"]); ?>
        <div class="w-full gap-4 flex flex-col" id="adr">
            <?php $form->print_fields(["voie", "residence", "cp", "ville", "pays"]); ?>
        </div>
    <?php }
    echo "<div class='mt-4'>";
    $form->submit("Créer");
    echo "</div>";
    $form->getError();
    $form->end();
    ?>
</div>
<script src="resources/js/stepsForm.js"></script>
