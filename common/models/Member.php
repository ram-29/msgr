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
            [['id', 'name'], 'required'],
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
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return [
            'threads' => function($x) {
                return \yii\helpers\ArrayHelper::getColumn($x->threadMembers, function($thm) {

                    $th = \common\models\Thread::findOne($thm['thread_id']);
                    $cfg = \common\models\ThreadGlobalConfig::findOne($thm['thread_id']);

                    // Get thread name.
                    $name = null;
                    if($th->type == 'GROUP') {
                        $name = $cfg->name;
                    } else {
                        $mMember = \yii\helpers\ArrayHelper::getColumn($th->threadMembers, 'member_id');

                        if (($key = array_search($thm['member_id'], $mMember)) !== false) {
                            unset($mMember[$key]);
                            $name = \common\models\Member::findOne(array_values($mMember))->name;
                        }
                    }

                    // Get recent message.
                    $message = null;
                    if(!empty($mMsgs = $th->getThreadMessages()->orderBy(['created_at' => SORT_DESC])->all())) {
                        $latest = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'text');
                        $time = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'created_at');

                        $message = compact("latest", "time");
                    }

                    return [
                        'id' => $th->id,
                        'type' => $th->type,
                        'name' => $name,
                        'message' => $message
                    ];
                });
            }
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
}
