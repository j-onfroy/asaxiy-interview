<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form= ActiveForm::begin([
    'id'=>'job-form',
    'options'=>[
        'class'=>'form-horizontal'
    ]
]);

echo $form->field($model, 'interview_date')->textInput(['value' => Yii::$app->request->get('interview_date') ?? $model->interview_date]);
echo $form->field($model, 'interview_time')->textInput(['value' => Yii::$app->request->get('interview_time') ?? $model->interview_time]);
echo $form->field($model, 'note_message')->textInput(['value' => Yii::$app->request->get('note_message') ?? $model->note_message]);
echo Html::submitButton('Yuborish', ['class' => 'btn btn-success']);

ActiveForm::end(); ?>