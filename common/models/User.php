<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use common\models\BaseModel;
use yii\web\UploadedFile;
use mongosoft\file\UploadBehavior;
use common\helpers\UploadFileHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    public $password;
    public $repassword;
    public $phone;
    public $nickname;
    public $avatar;
    public $intro;
    public $birth;
    public $address;
    public $profession;

    public $oldAvatar;

    private $_strong_pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z]).{6,}$/';

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['username', 'filter', 'filter' => 'trim', 'on'=>['register', 'create']],
            ['username', 'required', 'message'=> '用户名不能为空', 'on'=>['register', 'create']],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '此用户名已被占用.', 'on'=>['register', 'create']],
            ['username', 'string', 'min' => 2, 'max' => 255, 'on'=>['register', 'create']],

            ['email', 'filter', 'filter' => 'trim', 'on'=>['register', 'create']],
            ['email', 'required', 'message' => 'Email不能为空', 'on'=>['register', 'create']],
            ['email', 'email', 'message'=> 'Email格式不正确', 'on'=>['register', 'create']],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '此邮箱已被占用.', 'on'=>['register', 'create']],

            ['password', 'required', 'message'=> '密码不能为空', 'on'=>['register', 'create']],
            ['password','match','pattern'=>$this->_strong_pattern,'message'=>'密码太简单,必须包含字母和数字,不少于6位字符', 'on'=>['register', 'create','update']],
            ['repassword', 'required', 'message' => '确认密码不能为空', 'on'=>['register', 'create']],
            ['repassword', 'compare', 'compareAttribute'=>'password','message'=>'两次密码必须一致', 'on'=>['register', 'create', 'update']],
            
            [['avatar', 'phone', 'intro', 'address', 'birth', 'nickname'], 'safe'],
            ['avatar', 'file', 'extensions' => ['jpg','png','jpeg','gif','bmp'], 'maxSize' => 5*1024*1024*1024, 'on' => ['update','create'],'checkExtensionByMimeType'=>false],

        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', '用户名'),
            'phone' => Yii::t('app', '手机号码'),
            'status' => Yii::t('app', '状态'),
            'password' => Yii::t('app', '密码'),
            'repassword' => Yii::t('app', '确认密码'),
            'birth' => Yii::t('app', '出生日期'),
            'nickname' => Yii::t('app', '昵称'),
            'address' => Yii::t('app', '地址'),
            'intro' => Yii::t('app', '简介'),
            'avatar' => Yii::t('app', '头像'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    public function getUserInfo(){
        return $this->hasOne(UserInfo::className(), ['user_id' => 'id']);  

    }

    /**
     * create user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function createUser()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            // var_dump(UploadedFile::getInstance($this, 'avatar'));exit;
            try {
                $user = new self();
                $user->username = $this->username;
                $user->email = $this->email;
                $user->setPassword($this->password);
                $user->status = $this->status;
                $user->generateAuthKey();
                if ($user->save()) {
                    $userInfo = new UserInfo();
                    $userInfo->scenario = 'create';
                    $userInfo->nickname = empty($this->nickname) ? 'test-'.$this->username : $this->nickname;
                    $userInfo->phone = $this->phone;
                    $userInfo->address = $this->address;
                    $userInfo->intro = $this->intro;
                    $userInfo->birth = $this->birth;
                    $userInfo->user_id = $user->id;

                    // $upload = new UploadFileHelper();
                    // $upload->dir = '/user/avatar/';
                    // $image = $upload->uploadImage($this, $userInfo, 'avatar');
                    // var_dump($image);exit;

                    if($userInfo->save()){
                        // if ($image !== false) {
                        //     $path = $upload->getImageFile($userInfo, 'avatar');
                        //     $image->saveAs($path);
                        // }
                        $transaction->commit();
                        return $user;
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

    public function updateUser(){
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if(!empty($this->password) && !empty($this->repassword)){
                    $this->setPassword($this->password);
                    $this->generateAuthKey();
                }

                if ($this->save()) {
                    $userInfo = $this->userInfo;
                    $userInfo->scenario = 'update';
                    $userInfo->nickname = empty($this->nickname) ? 'test-'.$this->username : $this->nickname;
                    $userInfo->phone = $this->phone;
                    $userInfo->address = $this->address;
                    $userInfo->intro = $this->intro;
                    $userInfo->birth = $this->birth;

                    // $upload = new UploadFileHelper();
                    // $upload->dir = '/user/avatar/';
                    // $image = $upload->uploadImage($this, $userInfo, 'avatar');

                    // $this->oldAvatar = $userInfo->avatar;
                    // var_dump($this->oldAvatar);exit;
                    // $image = false;
                    // if($image)){
                    //     $upload = new UploadFileHelper();
                    //     $upload->dir = '/user/avatar/';
                    //     $image = $upload->uploadImage($this, $userInfo, 'avatar');
                    // }else{
                    //     $userInfo->avatar = $this->avatar;
                    //     unlink(Yii::$app->params['uploadPath'].'/user/avatar'.$this->oldAvatar);
                    // }
                    
                    if($userInfo->save()){
                        // if ($image !== false) {
                        //     $path = $upload->getImageFile($userInfo, 'avatar');
                        //     if($image->saveAs($path)){
                        //         @unlink(Yii::$app->params['uploadPath'].'/user/avatar/'.$this->oldAvatar);
                        //     }
                        // }
                        $transaction->commit();
                        return $user;
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

    // public function behaviors()
    // {
    //     // var_dump(UploadedFile::getInstance($this, 'avatar'));exit;
    //     return [
    //         [
    //             'class' => UploadBehavior::className(),
    //             'attribute' => 'userInfo.avatar',
    //             'scenarios' => ['create', 'update'],
    //             'generateNewName'=>true,
    //             'path' => 'upload/user/avatar/{id}',
    //             'url' => '@web/upload/user/avatar/{id}',
    //         ],
    //     ];
    // }
}
