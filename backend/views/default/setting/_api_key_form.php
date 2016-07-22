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
use yii\helpers\Url;
use kartik\markdown\MarkdownEditor;
// use yii\bootstrap\ActiveForm;
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig'=>['labelSpan'=>2], 'options'=>['enctype'=>'multipart/form-data']]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'name' => [
            'type'=>Form::INPUT_TEXT, 
            'options'=>['placeholder'=>'名称'],
        ]
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'accessKey' => [
            'type'=>Form::INPUT_TEXT, 
            'options'=>['placeholder'=>''],
        ]
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'secretKey' => [
            'type'=>Form::INPUT_TEXT, 
            'options'=>['placeholder'=>''],
        ]
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'status' => [   // complex nesting of attributes along with labelSpan and colspan
            'label'=>Yii::t('app', '状态'),
            'labelSpan'=>2,
            'columns'=>3,
            'attributes'=>[
                'status'=>[
                    'type'=>Form::INPUT_WIDGET, 
                    'widgetClass'=>'\kartik\widgets\SwitchInput', 
                    'options' => [
                        'pluginOptions' => [
                            'onText' => '正常',
                            'offText' => '关闭',
                        ]
                    ],

                ],
            ]
        ]
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

ActiveForm::end();

?>




