<?php

namespace app\controllers;
use app\models\Interview;
use app\models\Vacancy;
use Yii;
use yii\base\InvalidRouteException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    public function actionIndex()
    {
        $query = Vacancy::find();
        $sort = new Sort([
            'attributes' => [
                'job_title',
                'skills',
                'job_about'
            ]
        ]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2
            ],
            'sort' => $sort
        ]);
        return $this->render('index', [
            'dataProvider' => $provider
        ]);
    }

    public function actionView($id)
    {

        return $this->render('view',['id'=>$id]);
    }

    public function actionCreateJob()
    {
        $model = new Vacancy();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                try {
                    Yii::$app->getSession()->setFlash('success', 'Data saved !!!!');
                    return $this->redirect(['admin/index']);
                } catch (InvalidRouteException $e) {
                }
            }
        }

        return $this->render('create-job',
            ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Vacancy::findOne(['id' => intval($id)]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Data updated !!!!');
            return $this->redirect('admin/index');
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id): \yii\web\Response
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'User deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Failed in delete');
        }
        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel($id): ?Vacancy
    {
        if (($model = Vacancy::findOne($id)) != null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}