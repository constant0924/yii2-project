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
use kartik\markdown\MarkdownEditor;
use pendalf89\filemanager\widgets\TinyMCE;
use pendalf89\filemanager\widgets\FileInput;
// use yii\bootstrap\ActiveForm;
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig'=>['labelSpan'=>2], 'options'=>['enctype'=>'multipart/form-data']]);
echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'title' => [
            'type'=>Form::INPUT_TEXT, 
            'options'=>['placeholder'=>'请输入标题'],
        ]
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'thumbnail' => [   // complex nesting of attributes along with labelSpan and colspan
            'label'=>Yii::t('app', '略缩图'),
            'labelSpan'=>2,
            'columns'=>2,
            'attributes'=>[
                'thumbnail' => [
                    'type'=>Form::INPUT_WIDGET,
                    // 'widgetClass'=>'\kartik\widgets\FileInput',
                    'widgetClass' => '\pendalf89\filemanager\widgets\FileInput',
                    'options' => [
                        // 'options' => ['accept' => 'image/*', 'multiple'=>true],
                        // 'pluginOptions' => [
                        //     'maxFileCount' => 1,
                        //     'overwriteInitial'=>false,
                        //     'previewFileType' => 'image',
                        //     'showUpload' => false,
                        //     'overwriteInitial'=> !$model->thumbnail ? false : true,
                        //     'initialPreview'=> !$model->thumbnail ? [] : [Html::img(Yii::$app->params['uploadUrl'].'/post/thumbnail/'.$model->id. DIRECTORY_SEPARATOR .$model->thumbnail, ['width' => 120, 'height' => 150])]
                        // ]   
                        'buttonTag' => 'button',
                        'buttonName' => '相册选择',
                        'buttonOptions' => ['class' => 'btn btn-info'],
                        'options' => ['class' => 'form-control'],
                        // Widget template
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div><div class="img" id="post-thumbnail" style="margin-top: 10px;"></div>',
                        // Optional, if set, only this image can be selected by user
                        'thumb' => 'original',
                        // Optional, if set, in container will be inserted selected image
                        'imageContainer' => '.img',
                        // Default to FileInput::DATA_URL. This data will be inserted in input field
                        'pasteData' => FileInput::DATA_ID,
                        // JavaScript function, which will be called before insert file data to input.
                        // Argument data contains file data.
                        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
                        // 'callbackBeforeInsert' => 'function(e, data) {
                        //     return data.id;
                        // }',            
                    ]
                ],
            ]
        ]
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'user_id' => [   // complex nesting of attributes along with labelSpan and colspan
            'label'=>Yii::t('app', '发布用户'),
            'labelSpan'=>2,
            'columns'=>3,
            'attributes'=>[
                'user_id'=>[
                    'type'=>Form::INPUT_WIDGET, 
                    'widgetClass'=>'\kartik\widgets\Select2', 
                    'options'=>['data'=>$model->getPostAuthor()], 
                ],
            ]
        ]
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'category' => [   // complex nesting of attributes along with labelSpan and colspan
            'label'=>Yii::t('app', '所属分类'),
            'labelSpan'=>2,
            'columns'=>3,
            'attributes'=>[
                'category_id'=>[
                    'type'=>Form::INPUT_WIDGET, 
                    'widgetClass'=>'\kartik\widgets\Select2', 
                    'options'=>['data'=>Category::getCategorysSelect()], 
                ],
            ]
        ]
    ]
]);
echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'source' => [   // complex nesting of attributes along with labelSpan and colspan
            'label'=>Yii::t('app', '来源'),
            'labelSpan'=>2,
            'columns'=>3,
            'attributes'=>[
                'source'=>[
                    'type'=>Form::INPUT_TEXT, 
                    'options'=>['placeholder'=>'请输入来源'],
                ],
            ]
        ]
    ]
]);
echo Form::widget([       // 1 column layout
    'model'=>$model,
    'form'=>$form,
    'columns'=>1,
    'attributes'=>[
        'desc'=>['type'=>Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'请输入文章简介...']],
    ]
]);

// echo $form->field($model, 'content')->widget(TinyMCE::className(), [
//     'clientOptions' => [
//         // 'language' => 'ru',
        
//     ],
// ]);
echo Form::widget([       // 1 column layout
    'model'=>$model,
    'form'=>$form,
    'columns'=>1,
    'attributes'=>[
        'content'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\pendalf89\filemanager\widgets\TinyMCE', 
            'options' => [
                'clientOptions' => [
                    // 'toolbars' => [
                    //     [
                    //         'source', 'fullscreen', 'undo', 'redo', '|',
                    //         'fontfamily', 'fontsize', 'paragraph',
                    //         'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                    //         'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                    //         'forecolor', 'backcolor', '|',
                    //         'lineheight', '|',
                    //         'indent', 'preview', 'help','|',
                    //     ],
                    //     [
                    //         'simpleupload', 'insertimage', 'edittable', 'edittd', 'inserttable', '|',
                    //         'cleardoc', 'link', 'emotion', 'spechars', 'searchreplace', '|',
                    //         'map', 'gmap', 'insertvideo' 
                    //     ],
                    //     [
                    //         'insertrow', 'insertcol', 'mergeright', 'mergedown', 'mergecells', '|',
                    //         'deleterow', 'deletecol', 'splittorows', 'splittocols', 'splittocells', 'deletecaption', '|',
                    //         'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', 'insertorderedlist', 'insertunorderedlist', '|',
                    //         'pagebreak', 'imagenone', 'imageleft', 'imageright', 'imagecenter', 'autotypeset', 'charts', '|'

                    //     ]
                    // ],
                    'menubar' => false,
                    'height' => 500,
                    'image_dimensions' => false,
                    'plugins' => [
                        'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table',
                    ],
                    'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image preview charmap | code',
                    'Content' => !empty($model->postContent->content) ? $model->postContent->content : $model->content,
                ]
            ],
        ],
    ]
]);

echo Form::widget([    
    // default grid columns
    'columns'=>2,
    'model' => $model,
    'form'=>$form, 
    'attributes'=>[
        'status' => [   // complex nesting of attributes along with labelSpan and colspan
            'label'=>Yii::t('app', '发布状态'),
            'labelSpan'=>2,
            'columns'=>3,
            'attributes'=>[
                'status'=>[
                    'type'=>Form::INPUT_WIDGET, 
                    'widgetClass'=>'\kartik\widgets\SwitchInput', 
                    'options' => [
                        'pluginOptions' => [
                            'onText' => '发布',
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




