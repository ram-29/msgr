<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "thread_member".
 *
 * @property string $id
 * @property string $thread_id
 * @property string $member_id
 * @property string $nickname
 * @property string $role
 *
 * @property Member $member
 * @property Thread $thread
 */
class ThreadMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thread_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'thread_id', 'member_id', 'role'], 'required'],
            [['role'], 'string'],
            [['id', 'thread_id', 'member_id'], 'string', 'max' => 36],
            [['nickname'], 'string', 'max' => 200],
            [['id'], 'unique'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member_id' => 'id']],
            [['thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::className(), 'targetAttribute' => ['thread_id' => 'id']],
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
            'thread_id' => 'Thread ID',
            'member_id' => 'Member ID',
            'nickname' => 'Nickname',
            'role' => 'Role',
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
    public function getThread()
    {
        return $this->hasOne(Thread::className(), ['id' => 'thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */    
    public static function findThreadByMember($sender, $recipient) {
        $thread = self::find()->select(['thread_id', 'type', 'member_id', 'nickname'])->joinWith(['thread'])->where(['member_id' => $sender , 'member_id' => $recipient])->one();

        if($thread->nickname == null) {
            $member = Member::find()->select(['name'])->joinWith(['threadMembers'])->where(['member_id' => $recipient])->one();
            $nName = $member->name;
        } else {
            $nName = $thread->nickname;
        }
       

        if (!count($thread)) {
            return null;
        }
                
        return [
            'type' =>  $thread->thread->type,
            'id' => $thread->thread_id,
            'name' => $nName
        ];
    }      
}
