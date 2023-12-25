<?php

namespace app\controllers;

use app\models\User;
use app\models\Vacancy;
use yii\base\InvalidRouteException;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class AdminController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>Vacancy::find(),
            'pagination'=>[
                'pageSize'=>1,
            ]
        ]);
        return $this->render('index',[
            'dataProvider'=>$dataProvider,
        ]);
    }

    public function actionCreateJob()
    {
        $model = new Vacancy();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
           if( $model->save()){
               try {
                   \Yii::$app->response->redirect(['admin/index']);
               } catch (InvalidRouteException $e) {
               }
           }
        }

        return $this->render('create-job',
            ['model' => $model]);
    }
}