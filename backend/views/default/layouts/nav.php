<?php 
use yii\widgets\Menu;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use mdm\admin\components\MenuHelper;
/* @var $this \yii\web\View */
/* @var $content string */

$menus = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, function($menu){
    $data = json_decode($menu['data'], true);
    if(!isset($data['icon'])){
        $data['icon'] = '';
    }    
    $route = $menu['route'];
    $label = $menu['name'];
    if(!empty($menu['children'])){
        $res = [
            'label' => $label, 
            'items' => $menu['children'],
            'options' => [],
            'url' => 'javascript:void(0)',
            'active' => $active,
        ];
    }else{
        $active = false;
        if(strpos($menu['route'], Yii::$app->controller->id)){
            $active = true;
        }
        $res = [
            'label' => $label, 
            'url' => [$route],
            // 'items' => $menu['children'],
            // 'active' => $active,
        ];
    }
    return  $res;
});
// var_dump($menus);exit;

$menuItems = [];
if (!Yii::$app->user->isGuest) {
    // $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    $menuItems[] = [
        'label' => '退出 (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => array_merge($menus, $menuItems),
    'activateParents' => true
]);
?>


