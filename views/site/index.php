<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'id' => 'person-form',
    'options' => [
        'class' => 'form-horizontal'
    ],
]);

echo Html::a('Apply for Job', ['site/apply-job'], ['class' => 'btn btn-primary']) . "<br>";
echo "<br>";
echo Html::a('Login with admin', ['admin/index'], ['class' => 'btn btn-primary']) . "<br>";

ActiveForm::end(); ?>