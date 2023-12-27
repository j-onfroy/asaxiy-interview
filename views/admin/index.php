<?php

use yii\bootstrap5\Html;
use yii\grid\GridView;

echo "<br>";
echo Html::a('Create job', ['admin/create-job'], ['class' => 'btn btn-success']);
echo "  ";
echo Html::a('View Resumes', ['view/view-resume'], ['class' => 'btn btn-primary']);
echo "  ";
echo Html::a('Interviews', ['view/interviews'], ['class' => 'btn btn-warning']) . "<br>";
echo "<br>";
?>





<?= GridView::widget([

    'dataProvider' => $dataProvider,


    'pager' => [
        'class' => '\yii\widgets\LinkPager',
        'pageCssClass' => 'page-link',
        'options' => ['class' => 'pagination'],
        'maxButtonCount' => 5,
        'nextPageLabel' => 'Keyingi',
        'prevPageLabel' => 'Oldingi'
    ],

    'columns' => [

        ['class' => 'yii\grid\SerialColumn'],

        'job_title',

        'skills',

        'job_about',

        ['class' => 'yii\grid\ActionColumn'],
    ],

]);
