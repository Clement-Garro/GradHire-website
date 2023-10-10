<?php

/** @var $model ProLogin */

use app\src\core\component\form\Form;
use app\src\model\Auth\ProLogin;

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

    <h1>Pro Login</h1>

    <?php $form = Form::begin('', 'post') ?>
    <div class="w-full gap-4 flex flex-col">

        <?php echo $form->field($model, 'email') ?>
        <?php echo $form->field($model, 'password')->passwordField() ?>
        <?php echo $form->checkbox($model, 'remember') ?>
        <button class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
            Submit
        </button>
        <a href="register">Créer un compte entreprise</a>
        <?= $model->getFirstError("login"); ?>
    </div>
    <?php Form::end() ?>
</div>