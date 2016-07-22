<?php

namespace common\models;

use Yii;
use common\models\BaseModel;

/**
 * This is the model class for table "{{%post_content}}".
 *
 * @property integer $id
 * @property integer $post_id
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class PostContent extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
