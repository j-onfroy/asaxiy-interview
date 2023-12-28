<!DOCTYPE html>
<html>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php

use yii\grid\GridView;
use yii\helpers\Html;

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
        [
            'attribute' => 'vacancy.job_title'
        ],
        [
            'attribute' => 'candidate.first_name',
            'label' => 'Candidate first name'
        ],
        [
            'label' => 'Resume',
            'format' => 'raw',
            'value' => function ($model) {
                $resumeUrl = $model->candidate->resume_url;
                $fileSize = '';

                if (strpos($resumeUrl, 'https') === 0) {
                    $headers = get_headers($resumeUrl, true);

                    if (isset($headers['Content-Length'])) {
                        $sizeInBytes = $headers['Content-Length'];
                        $fileSize = '(' . Yii::$app->formatter->asShortSize($sizeInBytes) . ')';
                    }
                }
                $resumeLink = Html::a('<i class="fas fa-file-alt"></i> Resume', $resumeUrl, ['target' => '_blank']);
                return $resumeLink . ' ' . $fileSize;
            }
        ],
        'interview_date',
        'interview_time',
        'note_message',
        'status',
        ['class' => 'yii\grid\ActionColumn',
            'template' => ' {view} {update}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-edit"></i>', ['view/response-resume', 'id' => $model->id], ['class' => 'btn btn-success']);
                },
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-eye"></i>', ['view/view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                },
            ],
            'contentOptions' => ['style' => 'white-space: nowrap;'],
        ],
    ],
]);
?>
</body>
</html>
<?= Html::a('Back', ['admin/index'], ['class' => 'btn btn-warning']) ?>