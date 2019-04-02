<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "thread".
 *
 * @property string $id
 * @property string $type
 * @property string $created_at
 *
 * @property ThreadGlobalConfig $threadGlobalConfig
 * @property ThreadMember[] $threadMembers
 * @property ThreadMemberConfig[] $threadMemberConfigs
 * @property ThreadMessage[] $threadMessages
 */
class Thread extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['type'], 'string'],
            [['created_at'], 'safe'],
            [['id'], 'string', 'max' => 36],
            [['id'], 'unique'],
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
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadGlobalConfig()
    {
        return $this->hasOne(ThreadGlobalConfig::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMembers()
    {
        return $this->hasMany(ThreadMember::className(), ['thread_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMemberConfigs()
    {
        return $this->hasMany(ThreadMemberConfig::className(), ['thread_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMessages()
    {
        return $this->hasMany(ThreadMessage::className(), ['thread_id' => 'id']);
    }
}
