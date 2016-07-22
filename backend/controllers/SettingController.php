<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\Helper;
use yii\helpers\ArrayHelper;
use yii\base\DynamicModel;
use common\models\dynamic\BaseSetting;
use common\models\ApiKey;
use common\models\search\ApiKey as ApiKeySearch;
use kartik\grid\EditableColumnAction;
use yii\helpers\Json;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class SettingController extends BaseController
{
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

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
           'editApiKey' => [                                       // identifier for your editable column action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => ApiKey::className(),                // the model for the record being edited
               'outputValue' => function ($model, $attribute, $key, $index) {
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
           ]
       ]);
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$model = new BaseSetting(['siteTitle', 'adminEmail', 'supportEmail', 'siteUrl', 'pageSize', 'language', 'backendTheme', 'uploadPath', 'uploadUrl']);

    	if(!empty(Yii::$app->params)){
    		foreach (Yii::$app->params as $key => $value) {
    			if(array_key_exists($key, $model->attributes)){
    				$model->{$key} = $value;
    			}
    		}
    	}
    	
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	if ($model->hasErrors()) {
	        	foreach ($model->getErrors() as $key => $value) {
	        		$model->addError($key);
	        	}
		    }

		    //共同的部分
			$m=array_intersect(Yii::$app->params,$model->attributes); 

			//老的数据
			$old=array_diff(Yii::$app->params,$m);

			//新的数据
			$new=array_diff($model->attributes, $m);

			if(!empty($new)){
				$params = array_merge(Yii::$app->params, $model->attributes);
				$file = Yii::getAlias('@common').DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php';
				$res = file_put_contents($file, "<?php \n"."return ".var_export($params,true).';');
				chmod($file, 0777); 
				
				Yii::$app->getSession()->setFlash('success', '修改成功');
			}else{
				Yii::$app->getSession()->setFlash('info', '提交成功，没有任何修改');
			}
        }

        return $this->render('index', [
	        	'model' => $model,
	        ]);
        
    }

    public function actionApiKey(){
    	$searchModel = new ApiKeySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('api_key', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateApiKey(){
    	$model = new ApiKey();

    	if($model->load(Yii::$app->request->post()) && $model->save()){

    		return $this->redirect(['setting/api-key']);
    	}

    	return $this->render('create_api_key', [
    		'model' => $model
    	]);
    }

    public function actionUpdateApiKey($id){
    	$model = $this->findApiKeyModel($id);

    	if($model->load(Yii::$app->request->post()) && $model->save()){
    		Yii::$app->getSession()->setFlash('success', '修改成功');
    	}

    	return $this->render('update_api_key', [
    		'model' => $model
    	]);
    }

    public function actionDeleteApiKey($id)
    {
        $this->findApiKeyModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionApiKeyBatchDelete(){
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {  
            $ids = implode(',', Yii::$app->request->post('pid'));

            $res = ApiKey::deleteAll('id IN ('.$ids.')');
            if($res > 0){
                echo Json::encode(['result' => true]);
                Yii::$app->end();
            }
        }  
        else  
            throw new \yii\web\HttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    protected function findApiKeyModel($id)
    {
        if (($model = ApiKey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
