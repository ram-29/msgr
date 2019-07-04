<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class TruncateController extends Controller
{
    private static $table = [
        // 'thread',
        // 'member',

        'thread',
        'thread_member',

        'thread_global_config',
        'thread_member_config',
    
        'thread_message',
        'thread_message_seen',
    ];

    public static function actionIndex()
    {
        self::truncateTable(self::$table, true);
    }

    private static function truncateTable($table, $isMany = false)
    {
        # Temporary disable foreign_key_checks.
        Yii::$app->db->createCommand('SET foreign_key_checks=0')->execute();
        
        if (!($isMany)) {
            Yii::$app->db->createCommand()->truncateTable($table)->execute();
        } else {
            foreach($table as $t) {
                Yii::$app->db->createCommand()->truncateTable($t)->execute();
            }
        }

        # Renable it.
        Yii::$app->db->createCommand('SET foreign_key_checks=1')->execute();
    }
}
