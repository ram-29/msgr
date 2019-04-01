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

    public static function getShortenedText($str, $len = 34)
    {
        return mb_strimwidth(Html::encode($str), 0, $len, ' ...');
    }
    
}