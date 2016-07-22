<?php

// use yii\helpers\Html;
// use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Category;
use yii\helpers\Url;
// use yii\bootstrap\ActiveForm;
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig'=>['labelSpan'=>3], 'options'=>['enctype'=>'multipart/form-data']]);
echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'username' => [
        	'type'=>Form::INPUT_TEXT, 
        	'options'=>['placeholder'=>'请输入用户名', 'disabled' => $model->scenario == 'update' ? true : false],
        ],
        'email' => [
        	'type'=>Form::INPUT_TEXT, 
        	'options'=>['placeholder'=>'请输入Email']
        ],
        'password' => [
        	'type'=>Form::INPUT_PASSWORD, 
        	'options'=>['placeholder'=>'******'],
        	'visible' => $model->scenario == 'create' ? true : false
        ],
        'repassword' => [
        	'type'=>Form::INPUT_PASSWORD, 
        	'options'=>['placeholder'=>'******']
        ],
        'intro' => [
        	'type'=>Form::INPUT_TEXTAREA, 
        	'options'=>['placeholder'=>'请输入简介...', 'value' => $model->userInfo->intro],
        ],
        'nickname' => [
        	'type'=>Form::INPUT_TEXT, 
        	'options'=>['placeholder'=>'请输入用户昵称', 'value' => $model->userInfo->nickname],
        ],
        'phone' => [
        	'type'=>Form::INPUT_TEXT, 
        	'options'=>['placeholder'=>'请输入手机号码', 'value' => $model->userInfo->phone]
        ],
        'address' => [
        	'type'=>Form::INPUT_TEXT, 
        	'options'=>['placeholder'=>'请输入地址', 'value' => $model->userInfo->address]
        ],
       //  'avatar' => [
       //  	'type'=>Form::INPUT_WIDGET,
       //  	'widgetClass'=>'\kartik\widgets\FileInput',
       //  	'options' => [
       //  		'options' => ['accept' => 'image/*', 'multiple'=>true],
			    // 'pluginOptions' => [
			    //     'maxFileCount' => 1,
			    //     'overwriteInitial'=>false,
			    //     'previewFileType' => 'image',
			    //     'showUpload' => false,
			    //     'overwriteInitial'=> !$model->userInfo->avatar ? false : true,
			    //     'initialPreview'=> !$model->userInfo->avatar ? [] : [Html::img(Yii::$app->homeUrl.'upload/user/avatar/'.$model->userInfo->avatar, ['width' => 120, 'height' => 150])]
			    // ]			    
       //  	]
       //  ],
        'birth' => [
        	'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\datecontrol\DateControl',
            'options'=>[
                'options'=>[
                    'options'=>['placeholder'=>'出生日期...']
                ],
                'displayFormat' => 'yyyy-MM-dd',
            ],
            'value' => $model->userInfo->birth
        ],
        'status'=>[
            'type'=>Form::INPUT_RADIO_LIST, 
            'items'=>[1 => '启用', 0 => '关闭'], 
            'options'=>['inline'=>true]
        ],
        // 'profession' => [
        //     'type'=>Form::INPUT_TEXT, 
        //     'options'=>['placeholder'=>'请输入职业', 'value' => $model->userInfo->profession]
        // ],
        // [1],
        // 'actions'=>[
        //     'type'=>Form::INPUT_RAW, 
        //     'value'=>'<div style="text-align: right; margin-top: 20px">' . 
        //         Html::resetButton(Yii::t('app', '重置'), ['class'=>'btn btn-default']) . ' ' .
        //         Html::submitButton(Yii::t('app', '提交'), ['type'=>'button', 'class'=>'btn btn-info']) . 
        //         '</div>'
        // ],
    ]
]);

echo Form::widget([ // hide label and extend input to full width
    'model'=>$model,
    'form'=>$form,
    'columns'=>1,
    'attributes'=>[
        'actions'=>[
            'type'=>Form::INPUT_RAW, 
            'value'=>'<div style="text-align: right; margin-top: 20px">' . 
                Html::resetButton(Yii::t('app', '重置'), ['class'=>'btn btn-default']) . ' ' .
                Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['type'=>'button', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) . 
                '</div>'
        ]
    ]
]);

// echo '<div class="text-right">' . Html::resetButton('Reset', ['class'=>'btn btn-default']) . '</div>';
// echo '<div class="text-right">' . Html::submitButton('提交', ['class'=>'btn btn-info']) . '</div>';

ActiveForm::end();

?>



