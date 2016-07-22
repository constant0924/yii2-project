<?php
namespace common\models\form;

use common\models\User;
use yii\base\Model;
use Yii;
use common\models\UserInfo;
use yii\web\UploadedFile;
use mongosoft\file\UploadBehavior;
use common\helpers\UploadFileHelper;

/**
 * User form
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repassword;
    public $phone;
    public $nickname;
    public $avatar;
    public $intro;
    public $birth;
    public $address;
    public $profession;
    public $status;

    private $_strong_pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z]).{6,}$/';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim', 'on'=>['register', 'create']],
            ['username', 'required', 'message'=> '用户名不能为空', 'on'=>['register', 'create']],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '此用户名已被占用.', 'on'=>['register', 'create']],
            ['username', 'string', 'min' => 2, 'max' => 255, 'on'=>['register', 'create']],

            ['email', 'filter', 'filter' => 'trim', 'on'=>['register', 'create']],
            ['email', 'required', 'message' => 'Email不能为空', 'on'=>['register', 'create']],
            ['email', 'email', 'message'=> 'Email格式不正确', 'on'=>['register', 'create']],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '此邮箱已被占用.', 'on'=>['register', 'create']],

            ['password', 'required', 'message'=> '密码不能为空', 'on'=>['register', 'create']],
            ['password','match','pattern'=>$this->_strong_pattern,'message'=>'密码太简单,必须包含字母和数字,不少于6位字符', 'on'=>['register', 'create']],
            ['repassword', 'required', 'message' => '确认密码不能为空', 'on'=>['register', 'create']],
            ['repassword', 'compare', 'compareAttribute'=>'password','message'=>'两次密码必须一致', 'on'=>['register', 'create']],
            [['avatar'], 'safe'],
            ['avatar', 'file', 'extensions' => ['jpg','png','jpeg','gif','bmp'], 'maxSize' => 5*1024*1024*1024, 'on' => ['update','create'],'checkExtensionByMimeType'=>false],
        ];
    }

    /**
     * create user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function create()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            // var_dump(UploadedFile::getInstance($this, 'avatar'));exit;
            try {
                $user = new User();
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

                    $upload = new UploadFileHelper();
                    $upload->dir = '/user/avatar/';
                    $image = $upload->uploadImage($this, $userInfo, 'avatar');
                    // var_dump($image);exit;

                    if($userInfo->save()){
                        if ($image !== false) {
                            $path = $upload->getImageFile($userInfo, 'avatar');
                            $image->saveAs($path);
                        }
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
            'avatar' => Yii::t('app', '头像')
        ];
    }

    // public function setAttribute($name, $value){
    //     $this->{$name} = $value;
    // }

    // public function getAttribute($name){
    //     return $this->getAttributeLabel($name);
    // }

    // public function behaviors()
    // {
    //     // var_dump(UploadedFile::getInstance($this, 'avatar'));exit;
    //     return [
    //         [
    //             'class' => UploadBehavior::className(),
    //             'attribute' => 'avatar',
    //             'scenarios' => ['create', 'update'],
    //             'generateNewName'=>true,
    //             'path' => 'upload/user/avatar/{id}',
    //             'url' => '@web/upload/user/avatar/{id}',
    //         ],
    //     ];
    // }
     

}
