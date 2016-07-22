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
use pendalf89\filemanager\models\Mediafile;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id' => 'Post',
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
            'attribute'=>'thumbnail',
            'width'=>'80px',
            'filter' => false,
            'value'=>function ($model, $key, $index, $widget) { 
                $mediafile = Mediafile::loadOneByOwner('post', $model->id, 'thumbnail');
                $imgUrl = empty($mediafile) ? '' : $mediafile->getThumbUrl('small');
                return Html::a(Html::img(Yii::$app->params['uploadUrl'].$imgUrl),  Yii::$app->params['uploadUrl'].$imgUrl, ['title'=>'查看图片', 'target'=> '_blank']);
            },
            'format'=>'raw'
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'title',
            'pageSummary'=>'title',
            'vAlign'=>'middle',
            'width'=>'300px',
            'readonly'=>function($model, $key, $index, $widget) {
                return (!$model->status); // do not allow editing of inactive records
            },
            'editableOptions'=> function ($model, $key, $index){
                return [
                    'header'=>'标题', 
                    'size'=>'md',
                    'formOptions' => ['action' => ['/post/editpost']]
                ];
            }
        ],
        [
            'attribute'=>'author_name', 
            'vAlign'=>'middle',
            'value'=>function ($model, $key, $index, $widget) { 
                return Html::a($model->author->username,  
                    Url::to(['/user/view', 'id' => $model->user_id]), 
                    ['title'=>'查看用户详情', 'target'=> '_blank']);
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(User::find()->orderBy('username')->asArray()->all(), 'id', 'username'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'用户名称'],
            'format'=>'raw'
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'category_id', 
            'vAlign'=>'middle',
            'width'=>'160px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->category->name;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>$categorys, 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
                'options' => ['placeholder' => '选择搜索分类'],
            ],
            'pageSummary'=>'category_id',
            'readonly'=>function($model, $key, $index, $widget) {
                return (!$model->status); // do not allow editing of inactive records
            },
            'editableOptions'=> function ($model, $key, $index) use($categorys){
                return [
                    'header'=> Yii::t('app', '分类'), 
                    // 'size'=>'md',
                    'format'=>Editable::FORMAT_BUTTON,
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'formOptions' => ['action' => ['/post/editpost']],
                    'options' => ['class'=>'form-control', 'prompt'=>'请选择分类...'],
                    'data' => $categorys,
                    'editableValueOptions'=>['class'=>'text-danger']
                ];
            },
        ],
        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status', 
            'vAlign'=>'middle',
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
            'template' => '{comment} {update} {delete} ',
            'buttons' => [
                'comment' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-comment"></span>', Url::to(['setting/update-api-key', 'id' => $model->id]));
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
            'content'=> Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'window.location.href="'. Yii::$app->homeUrl .'post/create ";']) . ' '.
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
        'heading'=>'<i class="glyphicon glyphicon-book"></i>  文章列表',
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
            var ids = $(\'#Post\').yiiGridView(\'getSelectedRows\');
              $.ajax({
                type: \'POST\',
                url : \'/post/batch-delete\',
                data : {pid: ids},
                success : function(res) {
                    $.pjax.reload({container:\'#Post-container\'});
                }
            });

        });
    });', \yii\web\View::POS_READY);
?>