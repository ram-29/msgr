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
    public $globalConfig;

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
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return [
            'global-config' => function($x) {
                $cfg = \common\models\ThreadGlobalConfig::findOne($x->id);
                unset($cfg->id);
                
                return $cfg;
            },
            'members' => function($x) {
                $members = array_map(function($mem) {
                    return [
                        'id' => $mem['member_id'],
                        'nickname' => $mem['nickname'],
                        'role' => $mem['role'],
                    ];
                }, \common\models\ThreadMember::findAll(['thread_id' => $x->id]));

                return $members;
            },
            'messages' => function($x) {

                $params = \Yii::$app->request->getQueryParams();
                $offset = !empty($params['offset']) ? $params['offset'] : 0;

                $thMsgs = array_map(function($thMsg) {
                    if (!empty($thMsg['file'])) {
                        $mPathInfo = pathinfo($thMsg['file']);

                        $mFile = \preg_replace('/(\.\.\/\w*\/\w*)/i', \common\helpers\Getter::getUrl(false), $thMsg['file']);
                        $mFileThumb = \preg_replace('/\/[^\/]*$/', '/thumb', $thMsg['file']).'/'.$mPathInfo['filename'].'-thumb.'.$mPathInfo['extension'];
                        $mFileThumb = \preg_replace('/(\.\.\/\w*\/\w*)/i', \common\helpers\Getter::getUrl(false), $mFileThumb);

                        return [
                            'member_id' => $thMsg['member_id'],
                            'text' => $thMsg['text'],
                            'file_path' => $mFile,
                            'file_thumb' => $mFileThumb,
                            'file_name' => $thMsg['file_name'],
                            'file_type' => $thMsg['file_type'],
                            'created_at' => date('M j, Y g:i a', strtotime($thMsg['created_at'])),
                            'deleted_by' => $thMsg['deleted_by'],
                        ];
                    }

                    return [
                        'member_id' => $thMsg['member_id'],
                        'text' => $thMsg['text'],
                        'file_path' => null,
                        'file_thumb' => null,
                        'file_name' => null,
                        'file_type' => null,
                        'created_at' => date('M j, Y g:i a', strtotime($thMsg['created_at'])),
                        'deleted_by' => $thMsg['deleted_by'],
                    ];
                    
                }, \array_reverse(\common\models\ThreadMessage::find()->where(['thread_id' => $x->id])->orderBy(['created_at' => SORT_DESC])->limit(10)->offset(10 * $offset)->all()));

                return $thMsgs;
            },
            'images' => function($x) {
                return array_map(function($img) {

                    $mPathInfo = pathinfo($img['file']);

                    $mFilePath = \preg_replace('/(\.\.\/\w*\/\w*)/i', \common\helpers\Getter::getUrl(false), $img['file']);
                    $mFileThumb = \preg_replace('/\/[^\/]*$/', '/thumb', $img['file']).'/'.$mPathInfo['filename'].'-thumb.'.$mPathInfo['extension'];
                    $mFileThumb = \preg_replace('/(\.\.\/\w*\/\w*)/i', \common\helpers\Getter::getUrl(false), $mFileThumb);

                    return [
                        // 'member_id' => $img['member_id'],
                        'file_path' => $mFilePath,
                        'file_thumb' => $mFileThumb,
                        'file_name' => $img['file_name'],
                        'created_at' => $img['created_at'],
                        'deleted_by' => $img['deleted_by'],
                    ];
                }, \common\models\ThreadMessage::find()->where(['thread_id' => $x->id, 'text' => null, 'file_type' => 'image'])->orderBy(['created_at' => SORT_DESC])->all());
            },
            'docs' => function($x) {
                return array_map(function($img) {

                    $mPathInfo = pathinfo($img['file']);

                    $mFilePath = \preg_replace('/(\.\.\/\w*\/\w*)/i', \common\helpers\Getter::getUrl(false), $img['file']);

                    return [
                        // 'member_id' => $img['member_id'],
                        'file_path' => $mFilePath,
                        'file_name' => $img['file_name'],
                        'created_at' => $img['created_at'],
                        'deleted_by' => $img['deleted_by'],
                    ];

                }, \common\models\ThreadMessage::find()->where(['thread_id' => $x->id, 'text' => null, 'file_type' => 'docs'])->orderBy(['created_at' => SORT_DESC])->all());
            }
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
