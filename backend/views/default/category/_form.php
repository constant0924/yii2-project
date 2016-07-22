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
// use yii\bootstrap\ActiveForm;
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig'=>['labelSpan'=>3]]);
echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form,
    
    // set global attribute defaults
    'attributeDefaults'=>[
        // 'type'=>Form::INPUT_TEXT,
        'labelOptions'=>['class'=>'col-md-2'], 
        'inputContainer'=>['class'=>'col-md-10'], 
        // 'container'=>['class'=>'form-group'],
    ],
    
    'attributes'=>[
        'name' => ['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'分类名称'],'columnOptions'=>['colspan'=>1]],
        'alias' => ['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'别名']],
        'parent_id' => ['type'=>Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\Select2', 'options'=>['data'=>ArrayHelper::merge(['请选择分类'],ArrayHelper::map(Category::getCategorysSelect(), 'id', 'name'))]],
        'status'=>['type'=>Form::INPUT_RADIO_BUTTON_GROUP,'items'=>['1' => '开启', '2' => '关闭']],
    ]
]);

echo '<div class="text-right">' . Html::submitButton('提交', ['class'=>'btn btn-info']) . '</div>';

ActiveForm::end();

?>


