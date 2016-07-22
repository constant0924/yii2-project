<?php
use Yii;
use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Category;
use yii\helpers\Url;
use kartik\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', '基本设置');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '用户管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
		<div class="user-create well">

		   	<?php 

				$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig'=>['labelSpan'=>2]]);
				echo '<legend>基本设置</legend>';
				echo Form::widget([    
				    // default grid columns
				    'columns'=>2,
				    'model' => $model,
				    'form'=>$form, 
				    'attributes'=>[
				        'siteTitle' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '网站标题'),
				            'labelSpan'=>2,
				            'columns'=>2,
				            'attributes'=>[
				                'siteTitle'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'请输入网站标题'],
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
				        'email' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '管理员邮箱'),
				            'labelSpan'=>2,
				            'columns'=>3,
				            'attributes'=>[
				                'adminEmail'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'admin@admin.com'],
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
				        'email' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '技术支持邮箱'),
				            'labelSpan'=>2,
				            'columns'=>3,
				            'attributes'=>[
				                'adminEmail'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'admin@admin.com'],
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
				        'email' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '网站链接'),
				            'labelSpan'=>2,
				            'columns'=>2,
				            'attributes'=>[
				                'supportEmail'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'http://xxx.com'],
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
				        'email' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '列表数据分页数'),
				            'labelSpan'=>2,
				            'columns'=>5,
				            'attributes'=>[
				                'pageSize'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'10'],
				                ],
				            ]
				        ]
				    ]
				]);

				echo Form::widget([    
				    'columns'=>2,
				    'model' => $model,
				    'form'=>$form, 
				    'attributes'=>[
				        'language' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '语言设置'),
				            'labelSpan'=>2,
				            'columns'=>3,
				            'attributes'=>[
				                'language'=>[
				                    'type'=>Form::INPUT_WIDGET, 
				                    'widgetClass'=>'\kartik\widgets\Select2', 
				                    'options'=>['data' => ['zh-CN' => '中文简体', 'en' => '英文']], 
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
				        'theme' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '后台主题名称'),
				            'labelSpan'=>2,
				            'columns'=>4,
				            'attributes'=>[
				                'backendTheme'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'theme'],
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
				        'upload' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '文件上传目录'),
				            'labelSpan'=>2,
				            'columns'=>2,
				            'attributes'=>[
				                'uploadPath'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'/upload/xxx'],
				            		'hint'=>'注意目录权限, /代表网站根目录'
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
				        'upload' => [   // complex nesting of attributes along with labelSpan and colspan
				            'label'=>Yii::t('app', '上传文件访问链接'),
				            'labelSpan'=>2,
				            'columns'=>2,
				            'attributes'=>[
				                'uploadUrl'=>[
				                    'type'=>Form::INPUT_TEXT, 
				            		'options'=>['placeholder'=>'http://xxx.com'],
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
				                Html::submitButton(Yii::t('app', '提交'), ['type'=>'button', 'class' => 'btn btn-primary']) . 
				                '</div>'
				        ]
				    ]
				]);

				ActiveForm::end();

		   	?>
		</div>
	</div>
</div>