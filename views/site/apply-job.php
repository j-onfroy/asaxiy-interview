<?php


use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'id' => 'person-form',
    'options' => [
        ['enctype' => 'multipart/form-data'],
        'class' => 'form-horizontal'
    ],
]);

echo Html::a('Apply for Job', ['admin/index'], ['class' => 'btn btn-primary'])."<br>";
echo "<br>";
echo $form->field($model, 'first_name')->textInput(['value' => Yii::$app->request->get('first_name') ?? $model->first_name]);
echo $form->field($model, 'last_name')->textInput(['value' => Yii::$app->request->get('last_name') ?? $model->last_name]);
echo $form->field($model, 'email')->input('email')->textInput(['value' => Yii::$app->request->get('email') ?? $model->email]);
echo $form->field($model, 'phone_number')->textInput(['value' => Yii::$app->request->get('phone_number') ?? $model->phone_number]);
echo $form->field($model, 'country_of_origin')->textInput(['value' => Yii::$app->request->get('country_of_origin') ?? $model->country_of_origin]);
echo $form->field($model, 'address')->textInput(['value' => Yii::$app->request->get('address') ?? $model->address]);
echo $form->field($model, 'resume_url')->fileInput(['value' => Yii::$app->request->get('resume_url') ?? $model->resume_url]);
echo $form->field($model, 'birthday')->widget(DatePicker::class,
    ['dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'changeYear' => true,
        ],
    ]
);
echo Html::submitButton('Yuborish', ['class' => 'btn btn-success']);

ActiveForm::end(); ?>
