<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property string $id
 * @property string $name
 * @property string $status
 * @property string $joined_at
 * @property string $logged_at
 *
 * @property ThreadMember[] $threadMembers
 * @property ThreadMemberConfig[] $threadMemberConfigs
 * @property ThreadMessage[] $threadMessages
 * @property ThreadMessageSeen[] $threadMessageSeens
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'username'], 'required'],
            [['status'], 'string'],
            [['joined_at', 'logged_at'], 'safe'],
            [['id'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 200],
            [['id'], 'unique'],
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
        $this->joined_at = date("Y-m-d H:i:s", time());
        $this->logged_at = null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'joined_at' => 'Joined At',
            'logged_at' => 'Logged At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMembers()
    {
        return $this->hasMany(ThreadMember::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMemberConfigs()
    {
        return $this->hasMany(ThreadMemberConfig::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMessages()
    {
        return $this->hasMany(ThreadMessage::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThreadMessageSeens()
    {
        return $this->hasMany(ThreadMessageSeen::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */    
    public static function findByUsername($username) {
        $user = self::find()->where(["username" => $username])->one();

        if (!count($user)) {
            return null;
        }
        
        return $user;
    }
           
}
