<?php

namespace app\controllers;

use app\models\Interview;
use Yii;
use yii\data\ArrayDataProvider;
use yii\mail\MessageInterface;
use yii\web\Controller;

class ViewController extends Controller
{
    public function actionViewResume()
    {
        $dataProvider = $this->getArrayDataProvider($this->getAll());

        return $this->render('view-resume', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResponseResume($id)
    {
        $model = Interview::findOne(['id' => intval($id)]);

        if ($model->load(Yii::$app->request->post())) {
            $model->status = "Intervyu Belgilangan";
            if ($model->save()) {
                $interview = $this->getInterview($id);
                $toEmail = $interview->candidate->email;
                $email = $this->sendMail($toEmail, $model);
                if ($email->send()) {
                    Yii::$app->getSession()->setFlash('success', 'Interview accepted ' . $toEmail);
                    return $this->redirect(['view-resume']);
                } else {
                    Yii::$app->session->setFlash("warning", 'Interview accepted but mail not send');
                    return $this->redirect('view-resume');
                }
            }
        }

        $interview = $this->getInterview($id);
        $toEmail = $interview->candidate->email;

        return $this->render('response-resume', [
            'model' => $model,
            'interview' => $interview,
        ]);
    }

    public function actionIgnore($id)
    {
        $interview = $this->getInterview($id);
        $toEmail = $interview->candidate->email;
        $email = $this->sendMail($toEmail, null);

        if ($email->send()) {
            $interview->status = "Qabul qilinmagan";
            if ($interview->save()) {
                Yii::$app->session->setFlash("success", 'reject mail send to ' . $toEmail);
                return $this->redirect('view-resume');
            }
        } else {
            Yii::$app->session->setFlash("warning", 'something is wrong');
            return $this->redirect('view-resume');
        }
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getInterview($id)
    {
        return Interview::find()
            ->innerJoinWith('vacancy')
            ->innerJoinWith('candidate')
            ->where(['interview.id' => $id])
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAll(): array
    {
        $interviews = Interview::find()
            ->innerJoinWith('vacancy')
            ->innerJoinWith('candidate')
            ->all();

        usort($interviews, function ($a, $b) {
            $jobTitleA = $a->vacancy->job_title;
            $jobTitleB = $b->vacancy->job_title;

            if ($jobTitleA !== $jobTitleB) {
                return strcmp($jobTitleA, $jobTitleB);
            } else {
                $dateA = strtotime($a->created_date);
                $dateB = strtotime($b->created_date);
                return $dateA <=> $dateB;
            }
        });
        return $interviews;
    }

    /**
     * @param array $interviews
     * @return ArrayDataProvider
     */
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

    /**
     * @param $toEmail
     * @return \yii\mail\MessageInterface
     */
    public function sendMail($toEmail, $model): MessageInterface
    {
        $mailer = Yii::$app->mailer;

        if ($model == null) {
            $subject = "Reject from Asaxiy";
            $html = "<b>Reject. We are currently unable to accept you for this positionReject</b>";
            $text = "We are currently unable to accept you for this position";
        } else {
            $message = $model->note_message;
            $date = $model->interview_date;
            $time = $model->interview_time;
            $subject = "Accepted your submission";
            $text = "We have scheduled an interview with you";
            $html = "<b>We invite you for an interview. Eslattma Boshliqdan </b>" . $message . ". Intervyu sanasi: " . $date . ". Intervyu vaqti: " . $time;

        }


        $email = $mailer->compose()
            ->setTo($toEmail)
            ->setFrom('doniy@doniyor.website')
            ->setSubject($subject)
            ->setTextBody($text)
            ->setHtmlBody($html);
        return $email;
    }
}