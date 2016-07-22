<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\search\PostSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;
use common\models\Category;
use yii\helpers\Json;
use common\Library\Qiniu;
use pendalf89\filemanager\models\Mediafile;
/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BaseController
{

    public function actions()
    {
        // $rootPath = \Yii::getAlias('@backend');
        return ArrayHelper::merge(parent::actions(), [
           'editpost' => [                                       // identifier for your editable column action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => Post::className(),                // the model for the record being edited
               'outputValue' => function ($model, $attribute, $key, $index) {
                    $res = $model->$attribute;
                    switch ($attribute) {
                        case 'category_id':
                            $res = $model->categorys->name;
                            break;
                    }
                    return $res;      // return any custom output value if desired
               },
               'outputMessage' => function($model, $attribute, $key, $index) {
                     return '';                                  // any custom error to return after model save
               },
               'showModelErrors' => true,                        // show model validation errors after save
               'errorOptions' => ['header' => ''],                // error summary HTML options
               'postOnly' => true,
               'ajaxOnly' => true,
               // 'findModel' => function($id, $action) {},
               // 'checkAccess' => function($action, $model) {}
           ],
           'upload' => [
                'class' => 'common\actions\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->params['uploadUrl'],//图片访问路径前缀
                    "imagePathFormat" => "/uedit/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageManagerListPath" => 'uedit/',
                ],
                // 'rootPath' => UPLOAD
            ]
       ]);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $categorys = Category::getCategorysSelect();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categorys' => $categorys
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->savePost()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $model->content = $model->postContent->content;
        
        if ($model->load(Yii::$app->request->post()) && $model->savePost()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBatchDelete(){
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {  
            $ids = implode(',', Yii::$app->request->post('pid'));

            $res = Post::deleteAll('id IN ('.$ids.')');
            if($res > 0){
                echo Json::encode(['result' => true]);
                Yii::$app->end();
            }
        }  
        else  
            throw new \yii\web\HttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
}
