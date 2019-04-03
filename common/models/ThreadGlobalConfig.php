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
 * @property Thread $thread
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'color' => 'Color',
            'emoji' => 'Emoji',
            'picx' => 'Picx',
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
    // public function setAttrs()
    // {
    //     $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::className(), ['id' => 'id']);
    }
}
