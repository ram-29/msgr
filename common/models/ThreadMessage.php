<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "thread_message".
 *
 * @property string $id
 * @property string $thread_id
 * @property string $member_id
 * @property string $text
 * @property string $file
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
            [['id', 'thread_id', 'member_id'], 'required'],
            [['text', 'deleted_by'], 'string'],
            [['created_at'], 'safe'],
            [['id', 'thread_id', 'member_id'], 'string', 'max' => 36],
            [['file'], 'string', 'max' => 200],
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
            'text' => 'Text',
            'file' => 'File',
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
    protected function setFlash($event)
    {
        $mName = \common\helpers\Getter::getModelName($event->sender);
        \common\helpers\Getter::setFlash("{$mName} | {$event->sender->id}", $event->name);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttrs()
    {
        $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
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
