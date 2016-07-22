<?php

namespace common\models;

use Yii;
use common\models\User;
use mongosoft\file\UploadBehavior;
use common\models\BaseModel;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $nickname
 * @property string $phone
 * @property string $birth
 * @property string $avatar
 * @property string $intro
 * @property string $profession
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserInfo extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['user_id', 'created_at', 'updated_at'], 'integer'],
            // [['nickname'], 'string', 'max' => 60],
            // [['phone'], 'string', 'max' => 30],
            // [['birth', 'profession'], 'string', 'max' => 50],
            // [['avatar', 'intro'], 'string', 'max' => 255],
            // [['address'], 'string', 'max' => 120],
            ['avatar', 'file', 'extensions' => ['jpg','png','jpeg','gif','bmp'], 'maxSize' => 5*1024*1024*1024, 'on' => ['update','create'],'checkExtensionByMimeType'=>false],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', '用户ID'),
            'nickname' => Yii::t('app', '昵称'),
            'phone' => Yii::t('app', '手机号码'),
            'birth' => Yii::t('app', '出生日期'),
            'avatar' => Yii::t('app', '头像'),
            'intro' => Yii::t('app', '简介'),
            'profession' => Yii::t('app', '职业'),
            'address' => Yii::t('app', '地址'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    // public function behaviors()
    // {
    //     var_dump(UploadedFile::getInstance($this, 'avatar'));exit;
    //     return array_merge_recursive(parent::behaviors(),[
    //         [
    //             'class' => UploadBehavior::className(),
    //             'attribute' => 'avatar',
    //             'scenarios' => ['create', 'update'],
    //             'generateNewName'=>true,
    //             'path' => '@webroot/upload/user/avatar/{id}',
    //             'url' => '@web/upload/user/avatar/{id}',
    //         ],
    //     ]);
    // }
}
