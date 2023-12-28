<?php

namespace app\controllers;

use app\models\Interview;
use DateTime;
use DateTimeZone;
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
            if ($model->status == "Intervyu Belgilangan") {
                $model->status = "Intervyu qayta belgilangan";
                $repeat = true;
            } else {
                $model->status = "Intervyu Belgilangan";
                $repeat = false;
            }
            if ($model->save()) {
                $interview = $this->getInterview($id);
                $toEmail = $interview->candidate->email;
                $email = $this->sendMail($toEmail, $model, $repeat);
                if ($email->send()) {
                    Yii::$app->getSession()->setFlash('success', 'Interview accepted ' . $toEmail);
                    return $this->redirect(['view-resume']);
                } else {
                    Yii::$app->session->setFlash("warning", 'Interview accepted but mail not send');
                    return $this->redirect('view-resume');
                }
            }
        }

        return $this->render('response-resume', [
            'model' => $model,
            'candidate' => $this->getInterview($id)->candidate,
        ]);
    }

    public function actionIgnore($id)
    {
        $interview = $this->getInterview($id);
        $toEmail = $interview->candidate->email;
        $email = $this->sendMail($toEmail, null, false);

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
            ->where(['or',
                ['status' => 'Yangi'],
                ['status' => 'Qabul qilinmagan'],
            ])
            ->all();

        usort($interviews, function ($a, $b) {
            $statusOrder = [
                'Yangi' => 0,
            ];

            $statusA = $a->status;
            $statusB = $b->status;

            $statusOrderA = $statusOrder[$statusA] ?? PHP_INT_MAX;
            $statusOrderB = $statusOrder[$statusB] ?? PHP_INT_MAX;

            if ($statusOrderA !== $statusOrderB) {
                return $statusOrderA - $statusOrderB;
            } else {

                $jobTitleA = $a->vacancy->job_title;
                $jobTitleB = $b->vacancy->job_title;

                if ($jobTitleA !== $jobTitleB) {
                    return strcmp($jobTitleA, $jobTitleB);
                } else {
                    $dateA = strtotime($a->created_date);
                    $dateB = strtotime($b->created_date);
                    return $dateA <=> $dateB;
                }
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
    public function sendMail($toEmail, $model, $repeat): MessageInterface
    {
        $mailer = Yii::$app->mailer;

        if ($model == null) {
            $subject = " Reject from Asaxiy ";
            $html = "<b> Reject. We are currently unable to accept you for this positionReject</b>";
            $text = " We are currently unable to accept you for this position ";
        } else if ($repeat) {
            $message = $model->note_message;
            $date = $model->interview_date;
            $time = $model->interview_time;
            $subject = "Interview date has changed. Text from Asaxiy";
            $text = "We have changed the date of the interview with you";
            $html = "<b>We invite you for again an interview. Eslattma Boshliqdan </b>" . $message . ". Intervyu sanasi: " . $date . ". Intervyu vaqti: " . $time;
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

    public function actionInterviews()
    {
        $dataProvider = $this->getArrayDataProvider($this->getAllInterviews());

        return $this->render('interviews', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        $model = $this->getInterview($id)->candidate;
        return $this->render("view", [
            'model' => $model
        ]);
    }

    /**
     * @throws \Exception
     */
    public function getAllInterviews()
    {
        $localTimeZone = new DateTimeZone('Asia/Tashkent');
        $currentTime = new DateTime('now', $localTimeZone);
        $currentTime = $currentTime->format('H:i:s');
        $currentDate = date('Y-m-d');

        return Interview::find()
            ->innerJoinWith('vacancy')
            ->innerJoinWith('candidate')
            ->where([
                'or',
                [
                    'and',
                    ['>', 'interview_date', $currentDate],
                    [
                        'or',
                        ['status' => 'Intervyu Belgilangan'],
                        ['status' => 'Intervyu qayta belgilangan']
                    ]
                ],
                [
                    'and',
                    ['interview_date' => $currentDate],
                    ['>', 'interview_time', $currentTime]
                ]
            ])
            ->orderBy([
                'interview_date' => SORT_ASC,
                'interview_time' => SORT_ASC
            ])
            ->all();
    }
}