<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%api_key}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $accessKey
 * @property string $secretKey
 * @property integer $status
 * @property integer $created_at
 */
class ApiKey extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%api_key}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at'], 'integer'],
            [['name', 'accessKey', 'secretKey'], 'string', 'max' => 60],
            [['name', 'accessKey', 'secretKey'], 'required'],
            [['name', 'accessKey', 'secretKey', 'status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'API-KEY名称'),
            'accessKey' => Yii::t('app', 'access密钥'),
            'secretKey' => Yii::t('app', 'secret密钥'),
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
