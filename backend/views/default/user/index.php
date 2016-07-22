<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\User;
use common\models\Category;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id' => 'User',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => '\kartik\grid\CheckboxColumn'
        ],
        [
            'attribute'=>'id',
            'filter' => false,
            'width'=>'40px',
            'vAlign'=>'middle',
        ],
        // [
        //     'label' => Yii::t('app', '头像'),
        //     'width'=>'80px',
        //     'filter' => false,
        //     'value'=>function ($model, $key, $index, $widget) { 
        //         return empty($model->userInfo->avatar) ? '未设置' : Html::img(Yii::$app->homeUrl.'upload/user/avatar/'.$model->userInfo->avatar, ['width' => 80, 'height' => 80]);
        //     },
        //     'format'=>'html',
        // ],
        [
            'attribute' => 'username',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(User::find()->orderBy('username')->asArray()->all(), 'id', 'username'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'用户名称'],
            'format'=>'raw',
            'width'=>'100px',
        ],
        [
            'attribute' => 'email',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(User::find()->orderBy('email')->asArray()->all(), 'id', 'email'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Email...'],
            'format'=>'raw',
            'width'=>'100px',
        ],

        [
            'attribute' => 'phone',
            'value' => 'userInfo.phone',
            'width'=>'100px',
            'format' => 'text',
            'filterInputOptions' => ['placeholder'=>'搜索手机号码','class'=>'form-control'],
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:Y-m-d'],
            'width'=>'90px',
            'vAlign'=>'middle',
            'filter' => false
        ],
        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status', 
            'vAlign'=>'middle',
        ], 
        [
            'class' => '\kartik\grid\ActionColumn',
            'header'=>'操作',
        ]
    ],
    'containerOptions' => ['style' => 'overflow: auto;'],
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => true,
    'toolbar' => [
        [
            'content' => Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('app', '添加用户'), 'class'=>'btn btn-success', 'onclick'=>'window.location.href="'. Yii::$app->homeUrl .'user/create ";']) . ' '.
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
        'heading'=>'<i class="glyphicon glyphicon-user"></i>  用户列表',
        'before' => '<div class="col-sm-2">'. Html::button('<i class="glyphicon glyphicon-trash"></i> 批量删除', ['type'=>'button', 'class'=>'btn btn-default', 'id' => 'batchAll']) .'</div>'
    ],
    'exportConfig'=>true,    

]);


$this->registerJs('
    $(function(){
        $(document).on(\'click\', \'#batchAll\', function(){ 
            var ids = $(\'#User\').yiiGridView(\'getSelectedRows\');
              $.ajax({
                type: \'POST\',
                url : \'/user/batch-delete\',
                data : {uid: ids},
                success : function(res) {
                    $.pjax.reload({container:\'#User-container\'});
                }
            });

        });
    });', \yii\web\View::POS_READY);

?>