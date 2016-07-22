<?php 
namespace backend\library;

use mdm\admin\Module;
/**
* 
*/
class Menu extends Module
{
	
	public static function getMenu(){
		$adminMenu = $this->getMenus();
		var_dump($adminMenu);exit;
	}
}