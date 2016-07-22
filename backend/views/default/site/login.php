<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'TestLee 后台登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
                
            <div class="row">  
                
                <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

                    <div class="form-container">
                        <div class="top-wrapper">
                            <div class="avatar-container">
                                <img src="<?php echo Yii::$app->homeUrl;?>images/default/login-avatar.jpg" id="avatar" class="avatar animated bounceIn" alt="avatar">
                            </div> 
                        </div>
                        <div class="bottom-wrapper">
                            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                                <?= $form->field($model, 'username',[
                                                        'template' => '{input}{error}',
                                                        'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter username', 'autofocus' => ''],
                                                        'inputTemplate' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>',
                                                    ]) ?>

                                <?= $form->field($model, 'password',[
                                                        'template' => '{input}{error}',
                                                        'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Enter password', 'autofocus' => ''],
                                                        'inputTemplate' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>',
                                                    ])->passwordInput() ?>

                                <div class="form-group">
                                    <?= Html::activeCheckbox($model, 'rememberMe',['class'=>'ace','labelOptions'=>['class'=>'inline'],'label'=>'<span class="lbl"> Remember Me</span>']) ?>
                                </div>
                                <div class="form-group">
                                    <?=  Html::submitButton('Login', ['class' => 'btn btn-green btn-block']) ?>
                                </div>
                                <div class="form-group">
                                    <hr>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
