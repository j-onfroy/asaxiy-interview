<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id'=>'person-form',
    'options'=>[
        'class'=>'form-horizontal'
    ],
]);

echo $form->field($model,'job_title')->textInput(['value'=> $model->job_title]);
echo $form->field($model,'skills')->textInput(['value'=> $model->skills]);
echo $form->field($model,'job_about')->input('email')->textInput(['value'=> $model->job_about]);
echo Html::submitButton('Yuborish',['class'=>'btn btn-success']);

ActiveForm::end(); ?>