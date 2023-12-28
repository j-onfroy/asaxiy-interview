<?php

namespace app\controllers;

use app\models\Interview;
use DateTime;
use DateTimeZone;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class InterviewController extends Controller
{
    /**
     * @throws \Exception
     */
    public function actionIndex()
    {
        $dataProvide = $this->getArrayDataProvider($this->getAllInterviews());
        return $this->render('index', [
            'dataProvider' => $dataProvide
        ]);
    }

    /**
     * @throws \Exception
     */
    public function getAllInterviews(): array
    {
        $localTimeZone = new DateTimeZone('Asia/Tashkent');
        $currentTime = new DateTime('now', $localTimeZone);
        $currentTime = $currentTime->format('H:i:s');
        $currentDate = date('Y-m-d');

        $interviews = Interview::find()
            ->innerJoinWith('vacancy')
            ->innerJoinWith('candidate')
            ->where([
                'or',
                [
                    'and',
                    ['<', 'interview_date', $currentDate],
                    [
                        'or',
                        ['status' => 'Intervyu Belgilangan'],
                        ['status' => 'Intervyu qayta belgilangan']
                    ]
                ],
                [
                    'and',
                    ['interview_date' => $currentDate],
                    ['<', 'interview_time', $currentTime]
                ]
            ])
            ->orderBy([
                'interview_date' => SORT_ASC,
                'interview_time' => SORT_ASC
            ])
            ->all();
        foreach ($interviews as $interview) {
            $interview->status = "Intervyu bo'lib o'tgan";
            $interview->save(false);
        }
        return $interviews;
    }

    public function getArrayDataProvider(array $interviews): ArrayDataProvider
    {
        return new ArrayDataProvider([
            'allModels' => $interviews,
            'pagination' => [
                'pageSize' => 2,
            ],
            'sort' => [
                'attributes' => ['id', 'created_date']
            ]
        ]);
    }

    public function actionOffer($id)
    {
        $interview = $this->getInterview($id);
        $interview->status = "Ishga qabul qilingan";
        $interview->candidate->hired = 1;

        if ($this->saveModels($interview, $interview->candidate)) {
            Yii::$app->session->setFlash("success", $interview->candidate->first_name . ' ' . $interview->vacancy->job_title . " yo'nalishiga ishga qabul qilindi");
        } else {
            Yii::$app->session->setFlash("error", "Failed to update the interview or candidate status");
        }

        return $this->redirect(['interview/index']);
    }

    public function actionReject($id)
    {
        $interview = $this->getInterview($id);
        $interview->status = "Ishga qabul qilinmadi";

        if ($this->saveModels($interview)) {
            Yii::$app->session->setFlash("success", $interview->candidate->first_name . ' ' . $interview->vacancy->job_title . " yo'nalishiga ishga qabul qilinmadi");
        } else {
            Yii::$app->session->setFlash("error", "Failed to update the interview or candidate status");
        }

        return $this->redirect(['interview/index']);
    }

    /**
     * Save models with error handling
     * @param mixed ...$models
     * @return bool
     */
    private function saveModels(...$models)
    {
        foreach ($models as $model) {
            if (!$model->save()) {
                return false;
            }
        }
        return true;
    }


    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getInterview($id)
    {
        return Interview::find()
            ->innerJoinWith('candidate')
            ->innerJoinWith('vacancy')
            ->where(['interview.id' => $id])
            ->one();
    }

}