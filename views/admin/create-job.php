<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form= ActiveForm::begin([
    'id'=>'job-form',
    'options'=>[
        'class'=>'form-horizontal'
    ]
]);

echo $form->field($model, 'job_title')->textInput(['value' => Yii::$app->request->get('job-title') ?? $model->job_title]);
echo $form->field($model, 'skills')->textInput(['value' => Yii::$app->request->get('skills') ?? $model->skills]);
echo $form->field($model, 'job_about')->textInput(['value' => Yii::$app->request->get('job_about') ?? $model->job_about]);
echo Html::submitButton('Yuborish', ['class' => 'btn btn-success']);

ActiveForm::end(); ?>
