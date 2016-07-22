<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
use common\models\User;
use common\models\Category;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'APP-KEY');
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id' => 'api-key',
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'columns'=>[
        [
            'class' => '\kartik\grid\CheckboxColumn'
        ],
        [
            'attribute'=>'id',
            'filter' => false,
            'width'=>'40px',
            'vAlign'=>'middle',
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'name',
            'pageSummary'=>'name',
            'vAlign'=>'middle',
            'width'=>'300px',
            'readonly'=>function($model, $key, $index, $widget) {
                return (!$model->status); // do not allow editing of inactive records
            },
            'editableOptions'=> function ($model, $key, $index){
                return [
                    'header'=>Yii::t('app', '接口名称'), 
                    'size'=>'md',
                    'formOptions' => ['action' => ['/setting/editApiKey']]
                ];
            }
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'accessKey',
            'pageSummary'=>'accessKey',
            'vAlign'=>'middle',
            'width'=>'300px',
            'readonly'=>function($model, $key, $index, $widget) {
                return (!$model->status); // do not allow editing of inactive records
            },
            'editableOptions'=> function ($model, $key, $index){
                return [
                    // 'header'=>Yii::t('app', '接口名称'), 
                    'size'=>'md',
                    'formOptions' => ['action' => ['/setting/editApiKey']]
                ];
            }
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'secretKey',
            'pageSummary'=>'secretKey',
            'vAlign'=>'middle',
            'width'=>'300px',
            'readonly'=>function($model, $key, $index, $widget) {
                return (!$model->status); // do not allow editing of inactive records
            },
            'editableOptions'=> function ($model, $key, $index){
                return [
                    // 'header'=>Yii::t('app', '接口名称'), 
                    'size'=>'md',
                    'formOptions' => ['action' => ['/setting/editApiKey']]
                ];
            }
        ],
        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status', 
            'vAlign'=>'middle',
            'trueLabel' => '正常', 
            'falseLabel' => '关闭'
        ], 
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:Y-m-d'],
            'width'=>'90px',
            'vAlign'=>'middle',
            'filter' => false
        ],
        [
            'class' => '\kartik\grid\ActionColumn',
            'header'=>'操作',
            'template' => '{update}　{delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['setting/update-api-key', 'id' => $model->id]));
                },
            ],
        ]
        
    ],
    'containerOptions'=>['style'=>'overflow: auto;'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar'=> [
        [
            'content'=> Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('app', '添加API-KEY'), 'class'=>'btn btn-success', 'onclick'=>'window.location.href="'. Yii::$app->homeUrl .'setting/create-api-key ";']) . ' '.
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('kvgrid', 'Reset Grid')])
        ],
        '{export}',
        '{toggleData}',
    ],
    // set export properties
    'export'=>[
        'fontAwesome'=>true
    ],
    // parameters from the demo form
    'bordered'=> true,
    'striped'=> true,
    'condensed'=> true,
    'responsive'=> true,
    'hover'=> true,
    'panel'=>[
        'heading'=>'<i class="glyphicon glyphicon-book"></i>  API-KEY列表',
        // 'before' => '<div class="col-sm-2">'.Select2::widget(['name' => 'deleteAll', 'data' => ['all' => '全选', 'invert' => '反选', 'clear' => '不全选'], 'options' => ['placeholder' => '选择操作...'], 'hideSearch' => true, 'pluginEvents' => [
        //         "change" => "function() { 
        //             var keys=$('#Post').yiiGridView('getSelectedRows'); console.log($(this).val())
        //         }",
        //     ], 'pluginOptions' => [
        //         'allowClear' => true
        //     ],
        // ]).'</div>',
        'before' => '<div class="col-sm-2">'. Html::button('<i class="glyphicon glyphicon-trash"></i> 批量删除', ['type'=>'button', 'class'=>'btn btn-default', 'id' => 'batchAll']) .'</div>'
    ],
    'exportConfig'=>true,
]);


$this->registerJs('
    $(function(){
        $(document).on(\'click\', \'#batchAll\', function(){ 
            var ids = $(\'#api-key\').yiiGridView(\'getSelectedRows\');
              $.ajax({
                type: \'POST\',
                url : \'/setting/api-key-batch-delete\',
                data : {pid: ids},
                success : function(res) {
                    $.pjax.reload({container:\'#api-key-container\'});
                }
            });

        });
    });', \yii\web\View::POS_READY);
?>