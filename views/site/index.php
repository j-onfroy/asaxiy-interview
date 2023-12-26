<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'id' => 'person-form',
    'options' => [
        'class' => 'form-horizontal'
    ],
]);

echo Html::a('Login with admin', ['admin/index'], ['class' => 'btn btn-primary']) . "<br>";

ActiveForm::end(); ?>

<div class="album">
    <?php foreach ($data as $item): ?>
        <div class="card">
            <h3><?= $item['job_title'] ?></h3>
            <p><?= $item['job_about'] ?></p>
            <p><?= $item['skills'] ?></p>
            <?= Html::a('Apply this Job', ['site/apply-job','id'=>$item['id']], ['class' => 'btn btn-primary'])?>
        </div>
    <?php endforeach; ?>
</div>


