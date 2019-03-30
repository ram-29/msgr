<?php

namespace common\helpers;

use Yii;
use yii\helpers\VarDumper;

/**
 * A standard class helper used for logging values. 
 * It uses Yii2 built-in VarDumper helper class under the hood.
 */
class Logger {

    /**
     * Main Log Function
     * @param mixed ...$args
     * @return mixed
     */
    public static function log(...$args)
    {
        VarDumper::dump($args, 10, true);
        exit;
    }

}
