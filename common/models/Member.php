<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use Underscore\Underscore as __;
use common\helpers\Logger;

use common\models\IntranetUserInfo;

/**
 * This is the model class for table "member".
 *
 * @property string $id
 * @property string $intranet_id
 * @property string $name
 * @property string $sex
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
    // Declare attributes not found on base table.
	public $name, $sex, $status, $joined_at, $logged_at, $intranet_id;

    // Specifies the default db connection for this model.
	public static function getDb() {
		return Yii::$app->intranet;
    }
    
    /**
     * {@inheritdoc}
     */
    public function afterFind() {
        parent::afterFind();

        $mUserInfo = $this->userInfo;

		// Match user information to declared attributes.
        $this->name = !empty($mUserInfo && $mUserInfo->fullName) ? 
            ucwords(mb_strtolower($mUserInfo->fullName)) : '';
        
        $this->sex = !empty($mUserInfo) ? 
            $mUserInfo->SEX_C : '';

        $this->status = 'ACTIVE';
		$this->joined_at = $this->created_at;
		$this->logged_at = $this->created_at;
        $this->intranet_id = $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        // return 'member';
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['sex', 'status'], 'string'],
            [['joined_at', 'logged_at'], 'safe'],
            [['id'], 'string', 'max' => 36],
            [['intranet_id', 'name'], 'string', 'max' => 200],
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
            'intranet_id' => 'Intranet ID',
            'name' => 'Name',
            'sex' => 'Sex',
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
                $mArr = \yii\helpers\ArrayHelper::getColumn($x->threadMembers, function($thm) use ($x) {
                    $th = \common\models\Thread::findOne($thm['thread_id']);
                    $cfg = \common\models\ThreadGlobalConfig::findOne($thm['thread_id']);

                    // Get thread name.
                    $name = null;
                    $sex = null;
                    if($th->type == 'GROUP') {
                        $name = $cfg->name;
                    } else {
                        $mMember = \yii\helpers\ArrayHelper::getColumn($th->threadMembers, 'member_id');
                        if (($key = array_search($thm['member_id'], $mMember)) !== false) {
                            unset($mMember[$key]);
                            $mmMember = \common\models\Member::findOne(array_values($mMember));
                            $name = $mmMember->name;
                            $sex = $mmMember->sex;
                        }
                    }

                    // Get recent message.
                    $message = null;
                    if(!empty($mMsgs = $th->getThreadMessages()->orderBy(['created_at' => SORT_DESC])->all())) {

                        $mId = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'id');
                        $mMsgSeen = \common\models\ThreadMessageSeen::findOne(['thread_message_id' => $mId]);
                        
                        $latest = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'text');
                        $time = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'created_at');
                        $sent_by = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'member_id');

                        $unread = empty($mMsgSeen->seen_at) ? true : false;

                        if(!empty(\yii\helpers\ArrayHelper::getValue($mMsgs[0], 'file')) && ($x->id === $sent_by)) {
                            $latest = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'file_type') == 'image' ?
                                'You sent an image.' : 'You sent a document.';
                        } else if (!empty(\yii\helpers\ArrayHelper::getValue($mMsgs[0], 'file'))){
                            $latest = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'file_type') == 'image' ?
                                'Sent an image.' : 'Sent a document.';
                        }

                        $message = compact("latest", "time", "sent_by", "unread");
                    }

                    return $th->type == 'GROUP' ? [
                        'id' => $th->id,
                        'type' => $th->type,
                        'name' => $name,
                        'message' => $message,
                    ] : [
                        'id' => $th->id,
                        'type' => $th->type,
                        'name' => $name,
                        'sex' => $sex,
                        'message' => $message,
                    ];
                });

                // Sort by key.
                \usort($mArr, function($a, $b) {
                    if((!empty($a['message']))) {
                        return strtotime($a['message']['time']) - strtotime($b['message']['time']);
                    }
                });

                return array_reverse($mArr);
            },
            'unread_count' => function($x) {
                return array_sum(\yii\helpers\ArrayHelper::getColumn($x->threadMembers, function($thm) {
                    $th = \common\models\Thread::findOne($thm['thread_id']);

                    // Get recent message.
                    if(!empty($mMsgs = $th->getThreadMessages()->orderBy(['created_at' => SORT_DESC])->all())) {

                        $mId = \yii\helpers\ArrayHelper::getValue($mMsgs[0], 'id');
                        $mMsgSeen = \common\models\ThreadMessageSeen::findOne(['thread_message_id' => $mId]);
                        
                        return empty($mMsgSeen->seen_at) ? 1 : 0;
                    }

                    return 0;
                }));
            },
            'threads_group' => function($x) {
                $mMember = \common\models\Member::findOne($x->id);

                if($mMember) {
                    return array_filter(array_map(function($mThMember) {
                        $mThMem = $mThMember->getThread()->where(['type' => 'GROUP'])->asArray()->one();
                        if(!empty($mThMem)) {
                            $mmTh = \common\models\Thread::findOne($mThMem['id']);

                            $mmThConfig = $mmTh->getThreadGlobalConfig()->one();
                            unset($mmThConfig['id']);

                            $mThMem['global_config'] = $mmThConfig;
                            
                            $mThMem['members'] = array_map(function($mMem) {
                                $mMem['id'] = $mMem['member_id'];
                                unset($mMem['thread_id']); unset($mMem['member_id']);
                                return $mMem;
                            }, $mmTh->getThreadMembers()->all());

                            return $mThMem;
                        }
                    }, $mMember->getThreadMembers()->all()));
                }
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

    /**
     * @return \yii\db\Query
     */
    public function getIntranetUsers($offset = 0)
    {
        $query = (new Query())
            ->select(new Expression('
                U.id AS intranet_id,
                UPPER(CONCAT_WS(" ", UI.FIRST_M, UI.LAST_M)) AS name,
                IF(STRCMP(UPPER(UI.SEX_C), "MALE") = 0, "M", "F") AS sex,
                "ACTIVE" AS status,
                null AS joined_at,
                null AS logged_at,
                U.username AS username,
                PR.gravatar_id AS gravatar,
                U.email AS email,
                UI.MOBILEPHONE AS mobile_phone,
                OFF.OFFICE_M AS office
            '))
            // ->from(['HR' => 'hris_user']) # HRIS
            ->from(['U' => 'user']) # INTRANET
            // ->leftJoin(['U' => 'user'], 'U.id = HR.user_id') # HRIS
            ->leftJoin(['UI' => 'user_info'], 'U.id = UI.user_id')
            ->leftJoin(['PR' => 'profile'], 'U.id = PR.user_id')
            ->leftJoin(['OFF' => 'tbloffice'], 'OFF.OFFICE_C = UI.OFFICE_C')
            ->leftJoin(['HR' => 'hris_user'], 'HR.user_id = U.id') # INTRANET
            ->limit(20)->offset(20 * $offset);

        $result = Yii::$app->intranet->createCommand($query->createCommand()->rawSql)->queryAll();

        return array_map(function($x) {
           
            $mMem = \common\models\Member::findOne(['id' => $x['intranet_id']]);

            // Has found in member table.
            if($mMem) {
                // Set the id.
                $x['id'] = $mMem->id;
            } else {

                // Force create the member.
                $mmMem = new \common\models\Member();
                $mmMem->setAttrs();

                $mmMem->intranet_id = $x['intranet_id'];
                $mmMem->name = $x['name'];
                $mmMem->sex = $x['sex'];
                $mmMem->status = $x['status'];
                $mmMem->joined_at = $x['joined_at'];
                $mmMem->logged_at = $x['logged_at'];
                $mmMem->username = $x['username'];
                $mmMem->gravatar = $x['gravatar'];
                $mmMem->email = $x['email'];
                $mmMem->mobile_phone = $x['mobile_phone'];
                $mmMem->office = $x['office'];
                $mmMem->save();

                // Set the id.
                $x['id'] = $mmMem->id;
            }

            return $x;

        }, $result);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(IntranetUserInfo::className(), ['user_id' => 'id']);
    }
}

