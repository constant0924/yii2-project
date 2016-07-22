<?php 
namespace common\models\dynamic;

use Yii;
use yii\base\DynamicModel;
/**
* 动态模型 - 基本设置类
*/
class BaseSetting extends DynamicModel
{
	
	function __construct(array $attributes = [], $config = [])
	{
		parent::__construct($attributes, $config);
	}

	public function rules(){
		return [
            [['siteTitle', 'adminEmail', 'supportEmail'], 'string', 'max' => 128],
            ['siteTitle', 'required', 'message' => '请输入网站标题'],
            [['adminEmail', 'supportEmail'], 'email', 'message' => '邮箱格式错误'],
            [['siteTitle', 'adminEmail', 'supportEmail', 'siteUrl', 'pageSize', 'language', 'backendTheme', 'uploadPath', 'uploadUrl'], 'safe']
        ];
	}


}