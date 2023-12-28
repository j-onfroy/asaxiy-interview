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
                        $fileSize = '(' . Yii::$app->formatter->asShortSize($sizeInBytes).')';
                    }
                }
                $resumeLink = Html::a('<i class="fas fa-file-alt"></i> Resume', $resumeUrl, ['target' => '_blank']);
                return $resumeLink . ' ' . $fileSize;
            }
        ],
        'status',
        'created_date',
        ['class' => 'yii\grid\ActionColumn',
            'template' => ' {view} {response} {delete}  {ignore}',
            'buttons' => [
                'response' => function ($url, $model, $key) {
                    if ($model->status == "Qabul qilinmagan") {
                        return "         ";
                    } else
                        return Html::a('Response', ['view/response-resume', 'id' => $model->id], ['class' => 'btn btn-success']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-trash"> </i>', ['view/delete', 'id' => $model->id], ['class' => 'btn btn-warning',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ]]);
                },
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-eye"></i>', ['view/view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                },
                'ignore' => function ($url, $model, $key) {
                    if ($model->status != "Qabul qilinmagan") {
                        return Html::a('<i class="fas fa-ban"></i>', ['view/ignore', 'id' => $model->id],
                            ['class' => 'btn btn-danger',
                                'title' => 'Reject',
                                'aria-label' => 'Ignore',
                            ]);
                    }
                    return 'Rejected';
                },
            ],
            'contentOptions' => ['style' => 'white-space: nowrap;'],
        ],
    ],

]);
?>
</body>
</html>
<?= Html::a('Back', ['admin/index'], ['class' => 'btn btn-warning'])?>