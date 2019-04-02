<?php

namespace backend\modules\api\controllers;

/**
 * Thread controller for the `Api` module
 */
class ThreadController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Thread';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
		$behaviors['bootstrap'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

		return $behaviors;
    }
}
