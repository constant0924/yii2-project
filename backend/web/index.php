<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('BASE_PATH', dirname(__DIR__). '/../');
define('BACKEND_ROOT', dirname(__DIR__). '/'); //后台根目录

define('COMMON', BACKEND_ROOT . '/../common/'); //公共配置目录
define('VENDOR', BACKEND_ROOT . '/../vendor/'); //第三方引入库目录
define('BACKEND_CONFIG', BACKEND_ROOT . '/config/'); //后台配置目录
define('UPLOAD', BACKEND_ROOT . '/../upload/');

define('PAGE_SIZE', 10); //全局公共分页大小

require(VENDOR . 'autoload.php');
require(VENDOR . 'yiisoft/yii2/Yii.php');
require(COMMON . 'config/bootstrap.php');
require(BACKEND_CONFIG . 'bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(COMMON . 'config/main.php'),
    require(COMMON . 'config/main-local.php'),
    require(BACKEND_CONFIG . 'main.php'),
    require(BACKEND_CONFIG . 'main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
