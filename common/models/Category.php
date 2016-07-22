<?php

namespace common\models;

use Yii;
use common\models\BaseModel;
use common\helpers\Helper;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $parent_id
 * @property integer $level
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    use \kartik\tree\models\TreeTrait {
        isDisabled as parentIsDisabled; // note the alias
    }
 
    /**
     * @var string the classname for the TreeQuery that implements the NestedSetQueryBehavior.
     * If not set this will default to `kartik  ree\models\TreeQuery`.
     */
    public static $treeQueryClass; // change if you need to set your own TreeQuery
 
    /**
     * @var bool whether to HTML encode the tree node names. Defaults to `true`.
     */
    public $encodeNodeNames = true;
 
    /**
     * @var bool whether to HTML purify the tree node icon content before saving.
     * Defaults to `true`.
     */
    public $purifyNodeIcons = true;
 
    /**
     * @var array activation errors for the node
     */
    public $nodeActivationErrors = [];
 
    /**
     * @var array node removal errors
     */
    public $nodeRemovalErrors = [];
 
    /**
     * @var bool attribute to cache the `active` state before a model update. Defaults to `true`.
     */
    public $activeOrig = true;
    
    
    /**
     * Note overriding isDisabled method is slightly different when
     * using the trait. It uses the alias.
     */
    public function isDisabled()
    {
        if (Yii::$app->user->identity->username !== 'admin') {
            return true;
        }
        return $this->parentIsDisabled();
    }

    /**
     * 获取所有分类
     */
    public static function getCategorysSelect()
    {
        $sql = 'SELECT * FROM {{%category}} WHERE `active`=:active';
        $res = Yii::$app->db->createCommand($sql)->bindValues([':active'=>1])->queryAll();
        $arr = [];
        foreach ($res as $key => $value) {
            $nodeLeft = $value['lft'];
            $nodeRight = $value['rgt'];
            $isChild = ($nodeRight == $nodeLeft + 1);
            
            if(!$isChild){
                $tmp = self::getChilds($value);
                if(!empty($tmp)){
                    foreach ($tmp as $k => $val) {
                        $arr[$value['name']][$val['id']] = $val['name'];
                    }
                    
                }
            }
        }
        return $arr;
    }

    /** 
     * Short description.  
    */  
    public static function getChilds($category)  
    {  
        $sql = "SELECT id, name FROM {{%category}} WHERE lft BETWEEN :lft AND :rgt AND root = :root AND id <> :id ORDER BY `lft` ASC";

        $res = Yii::$app->db->createCommand($sql)->bindValues([':lft' => $category['lft'], ':rgt' => $category['rgt'], ':root' => $category['id'], ':id' => $category['id']])->queryAll();

        return $res;
        // $Result = self::checkcatagory($CatagoryID);
        // $Lft = $Result['lft'];  
        // $Rgt = $Result['rgt'];  
        // $SeekSQL = "SELECT * FROM {{%category}} WHERE ";  
        // switch ($type) {  
        //     case "1":  
        //         $condition = " lft > {$Lft} AND rgt < {$Rgt}";  
        //         break;  
        //     case "2":  
        //         $condition = " lft >= $Lft AND rgt <= $Rgt ";  
        //         break;  
        //     case "3":  
        //         $condition =" lft < $Lft AND `rgt` > $Rgt";  
        //         break;   
        //     case "4":  
        //         $condition=" lft <= $Lft AND rgt >= $Rgt";   
        //         break;  
        //     default :  
        //         $condition=" lft > $Lft AND `rgt < $Rgt";  
        //         break;
        // }   

        // $SeekSQL .= $condition." ORDER BY `lft` ASC";  

        // $Sorts = Yii::$app->db->createCommand($SeekSQL)->queryAll(); 
        // return $Sorts;  
    } // end func  
      
      
    /** 
     * Short description.  
     * 取得直属父类 
     * Detail description 
     * @param      none 
     * @global     none 
     * @since      1.0 
     * @access     private 
     * @return     void 
     * @update     date time 
    */  
    public static function getparent($CatagoryID)  
    {  
        $Parent = self::getcatagory($CatagoryID,1);  
        return $Parent;  
    }

    public static function checkcatagory($CatagoryID)  
    {  
        //检测父类ID是否存在  
        $sql = "SELECT * FROM {{%category}} WHERE id =  :cid LIMIT 1";  
        $Result = Yii::$app->db->createCommand($sql)->bindValues([':cid'=>$CatagoryID])->queryOne();  
        if(count($Result)<1){  
            return []; 
        }  

        return $Result;    
    }
}
