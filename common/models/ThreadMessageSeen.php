<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "thread_message_seen".
 *
 * @property string $id
 * @property string $thread_message_id
 * @property string $member_id
 * @property string $seen_at
 *
 * @property Member $member
 * @property ThreadMessage $threadMessage
 */
class ThreadMessageSeen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thread_message_seen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thread_message_id', 'member_id', 'seen_at'], 'required'],
            [['seen_at'], 'safe'],
            [['id', 'thread_message_id', 'member_id'], 'string', 'max' => 36],
            [['id'], 'unique'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member_id' => 'id']],
            [['thread_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => ThreadMessage::className(), 'targetAttribute' => ['thread_message_id' => 'id']],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_message_id' => 'Thread Message ID',
            'member_id' => 'Member ID',
            'seen_at' => 'Seen At',
        ];
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
    public function getThreadMessage()
    {
        return $this->hasOne(ThreadMessage::className(), ['id' => 'thread_message_id']);
    }
}
