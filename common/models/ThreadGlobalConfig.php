<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "thread_global_config".
 *
 * @property string $id
 * @property string $name
 * @property string $color
 * @property string $emoji
 * @property string $picx
 *
 * @property Thread $
 */
class ThreadGlobalConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thread_global_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'string', 'max' => 36],
            [['name', 'color', 'emoji', 'picx'], 'string', 'max' => 200],
            [['id'], 'unique'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

   /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'color' => 'Color',
            'emoji' => 'Emoji',
            'picx' => 'Picx',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get()
    {
        return $this->hasOne(Thread::className(), ['id' => 'id']);
    }
}
