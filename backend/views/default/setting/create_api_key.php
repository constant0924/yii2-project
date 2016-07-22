<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = Yii::t('app', '创建API-KEY');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'API-KEY设置'), 'url' => ['setting/api-key']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
		<div class="user-create well">
			<div class="row">
				<div class="col-md-11">
					<?= $this->render('_api_key_form', [
				        'model' => $model,
				    ]) ?>
				</div>
			</div>
		</div>
	</div>
</div>
