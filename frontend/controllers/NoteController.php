<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\note\frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yuncms\note\models\Note;
use yuncms\note\models\NoteCollection;

/**
 * Class NoteController
 * @package yuncms\note\controllers
 */
class NoteController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'print', 'raw','download',],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'set-type', 'update', 'delete', 'collection'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 笔记首页
     */
    public function actionIndex()
    {
        $query = Note::find()->active();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->applyOrder(Yii::$app->request->get('order', 'new'));
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 创建新的笔记
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = New Note();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('note', 'Note created.'));
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * 修改页面
     *
     * @param integer $id
     * @return \yii\web\Response|string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        /** @var Note $model */
        $model = $this->findModel($id);
        if ($model->isAuthor()) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('note', 'Note updated.'));
                return $this->redirect(['view','id'=>$model->id]);
            }
            return $this->render('update', ['model' => $model]);
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    }

    /**
     * 设置笔记隐藏或公开
     * @param int $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionSetType($id)
    {
        $model = $this->findModel($id);
        if ($model->isAuthor()) {
            if($model->isPublic()){
                $model->setPrivate();
                Yii::$app->getSession()->setFlash('success', Yii::t('note', 'Notes have been made private.'));
                return $this->redirect(['index']);
            } else {
                $model->setPublic();
                Yii::$app->getSession()->setFlash('success', Yii::t('note', 'Notes have been made public.'));
                return $this->redirect(['index']);
            }
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    }

    /**
     * 查看笔记页面
     *
     * @param string $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model && ($model->isPublic() || $model->isAuthor())) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', Yii::t('note', 'Note does not exist.'));
            return $this->redirect(['index',]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->isAuthor()) {
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('note', 'Note has been deleted'));
            return $this->redirect(['index']);
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    }

    /**
     * 收藏文章
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCollection()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $source = $this->findModel(Yii::$app->request->post('model_id'));
        if (($collect = NoteCollection::find()->where(['model_id' => $source->id, 'user_id' => Yii::$app->user->getId()])->one()) != null) {
            $collect->delete();
            return ['status' => 'unCollect'];
        } else {
            $model = new NoteCollection();
            $model->subject = $source->title;
            $model->model_id = $source->id;
            $model->load(Yii::$app->request->post(), '');
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
            return ['status' => 'collected'];
        }
    }

    /**
     * 获取打印
     * @param string $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        if ($model && ($model->isPublic() || $model->isAuthor())) {
            return $this->render('print', [
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', Yii::t('note', 'Note does not exist.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * 获取原始笔记
     * @param string $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionRaw($id)
    {
        $model = $this->findModel($id);
        if ($model && ($model->isPublic() || $model->isAuthor())) {
            Yii::$app->response->format = Response::FORMAT_RAW;
            return $model->content;
        } else {
            Yii::$app->session->setFlash('success', Yii::t('note', 'Note does not exist.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * 下载原始笔记
     * @param string $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model && ($model->isPublic() || $model->isAuthor())) {
            return Yii::$app->response->sendContentAsFile($model->content, $model->title);
        } else {
            Yii::$app->session->setFlash('success', Yii::t('note', 'Note does not exist.'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Note the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Note::findOne($id)) != null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist');
    }
}