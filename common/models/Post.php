<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use mongosoft\file\UploadBehavior;
use pendalf89\filemanager\behaviors\MediafileBehavior;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 * @property string $author_name
 * @property integer $category_id
 * @property string $desc
 * @property string $thumbnail
 * @property string $source
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Post extends \common\models\BaseModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSE = 0;
    const STATUS_DELETED = 99;

    public $content;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 120, 'on'=>['update', 'create']],
            [['author_name', 'source'], 'string', 'max' => 50, 'on'=>['update', 'create']],
            [['desc'], 'string', 'max' => 200, 'on'=>['update', 'create']],
            [['thumbnail'], 'string', 'max' => 255, 'on'=>['update', 'create']],
            [['title', 'user_id', 'category_id', 'content','status'], 'required', 'on'=>['update', 'create']],
            [['content'], 'safe', 'on'=>['update', 'create']],
            ['status', 'default', 'value' => self::STATUS_ACTIVE, 'on'=>['update', 'create']],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_CLOSE], 'on'=>['update', 'create']],
            // ['thumbnail', 'file', 'extensions' => ['jpg','png','jpeg','gif','bmp'], 'maxSize' => 5*1024*1024*1024, 'on' => ['update','create'],'checkExtensionByMimeType'=>false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '标题'),
            'user_id' => Yii::t('app', '用户id'),
            'author_name' => Yii::t('app', '作者名称(用户名称)'),
            'category_id' => Yii::t('app', '所属分类'),
            'desc' => Yii::t('app', '描述'),
            'thumbnail' => Yii::t('app', '缩略图'),
            'source' => Yii::t('app', '来源'),
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'content' => Yii::t('app', '内容')
        ];
    }

    public function behaviors()
    {
        return [
            // [
            //     'class' => UploadBehavior::className(),
            //     'attribute' => 'thumbnail',
            //     'scenarios' => ['create', 'update'],
            //     'path' => BASE_PATH.Yii::$app->params['uploadPath'].'/post/thumbnail/{id}',
            //     'url' => Yii::$app->params['uploadUrl'].'/post/thumbnail/{id}',
            // ],
            [
                'class' => MediafileBehavior::className(),
                'name' => 'post',
                'attributes' => [
                    'thumbnail',
                ],
            ]
            
        ];
    }

    public function getAuthor()  
    {  
        // User has_one User via User.id -> id  
        return $this->hasOne(User::className(), ['id' => 'user_id']);  
    } 

    public function getCategory()  
    {  
        // Category has_many Category via Category.id -> category_id  
        return $this->hasOne(Category::className(), ['id' => 'category_id']);  
    } 

    public function getPostContent(){
        return $this->hasOne(PostContent::className(), ['post_id' => 'id']); 
    }

    public function getPostAuthor(){
        return ArrayHelper::map(UserInfo::find()->all(), 'user_id', 'nickname');
    }

    /**
     * 保存文章信息
     * @return boolean
     */
    public function savePost(){
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $this->title = Html::encode($this->title);
                $this->source = Html::encode($this->source);
                $this->desc = Html::encode($this->desc);                    

                if ($this->save()) {

                    if($this->scenario == 'create'){
                        $postContent = new PostContent;
                    }else{
                        $postContent = $this->postContent;
                    }

                    $postContent->content = HtmlPurifier::process($this->content);
                    $postContent->post_id = $this->id;

                    if($postContent->save()){
                        $transaction->commit();
                        return true;
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback();
                return false;
            }
        }
    }
}
