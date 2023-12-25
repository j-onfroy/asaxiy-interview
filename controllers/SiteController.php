<?php

namespace app\controllers;

use app\models\Candidate;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\InvalidRouteException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use yii\web\UploadedFile;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionApplyJob()
    {

        $model = new Candidate();

        $bucketName = 'interview-asaxiy';
        $accessKeyId = 'AKIASGR7SVNCMQBZVFPA';
        $secretAccessKey = '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';
        $region = 'eu-north-1';

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $accessKeyId,
                'secret' => $secretAccessKey
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $uploadedFile = UploadedFile::getInstance($model, 'resume_url');

            if ($uploadedFile !== null) {
                $uuid = Uuid::uuid4()->toString();
                $fileName = $uuid . 'resume' . '.' . $uploadedFile->extension;
                $fileTmpName = $uploadedFile->tempName;

                try {
                    $result = $s3->putObject([
                        'Bucket' => $bucketName,
                        'Key' => 'resumes/' . $fileName,
                        'Body' => fopen($fileTmpName, 'rb'),
                        'ACL' => 'public-read',
                    ]);

                    $awsFileUrl = $result['ObjectURL'];

                    $model->resume_url = $awsFileUrl;
                    if ($model->save()) {
                        echo "File uploaded to S3 and model saved successfully.";
                        Yii::$app->response->redirect(['site/about']);
                    } else {
                        echo "Failed to save model with S3 file URL.";
                    }
                } catch (AwsException $e) {
                    echo "Error uploading file to S3: " . $e->getMessage();
                }
            } else {
                echo "No file uploaded.";
            }
        } else {
            echo "Model data not loaded or validation failed.";
        }

        return $this->render('apply-job',
            ['model' => $model]);
    }
}
