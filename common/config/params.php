<?php
return [
	'siteTitle' => 'CodingLee的博客', //标题
    'adminEmail' => 'constant0924@gmail.com', //管理员邮箱
    'supportEmail' => 'support@testlee.com', //技术支持邮箱
    'siteUrl' => 'http://www.codinglee.com', //站点url
    'pageSize' => '10', //列表获取数据
    'language'=>'zh-CN', //网站语言
    'backendTheme' => 'default', //后台主题
    'uploadPath' => '/upload',
    'uploadUrl' => 'http://upload.testlee.com',
    // 'user.passwordResetTokenExpire' => 3600,
    // 七牛配置
    'qiniu' => [
    	'accessKey' => 'QJAG4323Qq9uvcrCJrjYhc4ntRnp-JXQg-wWnb-b',
    	'secretKey' => 'WJV5Zu2vibqpK2RVeDqfpXnff9kh0FD0FSSIQEdE',
    	'bucket' => 'testlee',
    ],
    //后台管理员允许访问控制
    'adminAllowActions' =>[
    	'site/*',//允许访问的节点，可自行添加
        // 'admin/*',//允许所有人访问admin节点及其子节点
        'debug/*',
        'upload/*',
        '*'
    ],
    
    'backendurlManager' => [
    	'enablePrettyUrl' => true,
        // 'suffix'=>'path',            
        'rules'=>[
            // '<module\w+>/<controller:\w+>/cid/<cid:\d+>'=>'<module>/<controller>/index',
            // '<module\w+>/<controller:\w+>/<id:\d+>'=>'<controller>/index',
            // '<controller:\w+>/<id:\d+>'=>'<controller>/view',                 
        ],
        'showScriptName'=>false,
    ],
];
