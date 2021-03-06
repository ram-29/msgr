<?php

namespace common\helpers;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use dominus77\sweetalert2\Alert;
use Underscore\Underscore as __;

use common\helpers\Logger;

/**
 * A standard getter class used for acquiring desired data.
 */
class Getter {

    public static function getUrl($isBackend = true, $isSecure = false, $isLocalhost = true) 
    {
        if($isLocalhost) {
            $url = "%s://localhost:%s/%s/%s/web";

            $protocol = !$isSecure ? 'http' : 'https';
            
            $port = !$isSecure ?
                Yii::$app->request->port :
                Yii::$app->request->securePort;

            $dirName = basename(dirname(Yii::getAlias('@backend')));

            $dirLoc = $isBackend ? 'backend' : 'frontend';

            return sprintf($url, $protocol, $port, $dirName, $dirLoc);
        } else {
            $url = Url::base(true);

            if($isBackend) {
                $url = str_replace('frontend', 'backend', $url);
            } else {
                $url = str_replace('backend', 'frontend', $url);
            }
            
            return $url;
        }
    }

    public static function getModelName($model)
    {
        return ucwords(strtolower(StringHelper::basename(get_class($model))));
    }

    public static function getShortenedText($str, $len = 34)
    {
        return mb_strimwidth(Html::encode($str), 0, $len, ' ...');
    }

    public static function setFlash($text, $type)
    {
        $opts = [
            'title' => 'Success!',
        ];

        switch($type) {
            case 'afterInsert':
                $opts['text'] = "You have successfully created {$text}";
            break;
            case 'afterUpdate':
                $opts['text'] = "You have successfully edited {$text}";
            break;
        }

        Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, [ $opts ]);
    }
}