<?php

namespace common\models;

use Yii;
use niksko12\user\models\UserInfo;

/**
 * This is the extended class for "niksko12\user\models\UserInfo".
 *
 */
class IntranetUserInfo extends UserInfo
{
    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        return Yii::$app->intranet;
    }
}