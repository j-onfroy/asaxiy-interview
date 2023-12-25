<?php

use yii\grid\GridView;
use yii\helpers\Html;
echo "Hello from admin";
echo "<br>";
echo Html::a('Create job', ['admin/create-job'], ['class' => 'btn btn-primary']) . "<br>";

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'job_title',
        'skills',
        'job_about',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ],
    ],
]);

