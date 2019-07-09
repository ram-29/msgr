<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "thread_message".
 *
 * @property string $id
 * @property string $thread_id
 * @property string $member_id
 * @property string $type
 * @property string $text
 * @property string $file
 * @property string $file_name
 * @property string $file_type
 * @property string $created_at
 * @property string $deleted_by
 *
 * @property Member $member
 * @property Thread $thread
 * @property ThreadMessageSeen[] $threadMessageSeens
 */
class ThreadMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thread_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'member_id'], 'required'],
            [['type', 'text', 'file', 'file_name', 'file_type', 'deleted_by'], 'string'],
            [['created_at'], 'safe'],
            [['id', 'thread_id', 'member_id'], 'string', 'max' => 36],
            [['id'], 'unique'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member_id' => 'id']],
            [['thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::className(), 'targetAttribute' => ['thread_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_id' => 'Thread ID',
            'member_id' => 'Member ID',
            'type' => 'Type',
            'text' => 'Text',
            'file' => 'File',
            'file_name' => 'File Name',
            'file_type' => 'File Type',
            'created_at' => 'Created At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, [ $this, 'setFlash' ]);
        $this->on(self::EVENT_AFTER_UPDATE, [ $this, 'setFlash' ]);

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function setAttrs()
    {
        $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
    }

    /**
     * {@inheritdoc}
     */
    protected function setFlash($event)
    {
        $mName = \common\helpers\Getter::getModelName($event->sender);
        \common\helpers\Getter::setFlash("{$mName} | {$event->sender->id}", $event->name);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::className(), ['id' => 'thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMessageSeens()
    {
        return $this->hasMany(ThreadMessageSeen::className(), ['thread_message_id' => 'id']);
    }
}
