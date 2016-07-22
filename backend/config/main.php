<?php
require(COMMON . 'library/BackendMain.php');
$params = array_merge(
    require(COMMON . 'config/params.php'),
    require(COMMON . 'config/params-local.php'),
    require(BACKEND_CONFIG . 'params.php'),
    require(BACKEND_CONFIG . 'params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => BACKEND_ROOT,
    'controllerNamespace' => 'backend\controllers',
    // 'bootstrap' => ['log'],
    'language'=> $params['language'],
    'defaultRoute' => 'site',
    'modules' => [
        'debug' => 'yii\debug\Module',
        'admin' => $Main,
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // 'downloadAction' => 'gridview/export/download',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
            // 'downloadAction' => 'gridview/export/download',
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ],
        'filemanager' => [
            'class' => 'pendalf89\filemanager\Module',
            // Upload routes
            'routes' => [
                // Base absolute path to web directory
                // 'baseUrl' => $params['uploadUrl'],
                // Base web directory url
                'basePath' => '@upload',
                // Path for uploaded files in web directory
                'uploadPath' => 'file',
            ],
            // Thumbnails info
            'thumbs' => [
                'small' => [
                    'name' => '小',
                    'size' => [80, 80],
                ],
                'medium' => [
                    'name' => '中',
                    'size' => [300, 200],
                ],
                'large' => [
                    'name' => '大',
                    'size' => [500, 400],
                ],
            ],
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager'=>$params['backendurlManager'],
        'view' => [
            'theme' => [
                'pathMap' => ['@backend/views' => '@backend/views/'. $params['backendTheme']],
                // 'baseUrl' => '@web/default',
            ],
        ],
        // 'assetManager' => [
        //     'bundles' => [
        //         'yii\web\JqueryAsset' => [
        //             'sourcePath' => null,
        //             'js' => []
        //         ],
        //     ],
        // ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => $params['adminAllowActions'],
    ],
    'params' => $params,
];
